<?php

namespace App\Http\Controllers\Api;

use App\Enums\CartType;
use App\Enums\CourseBundleStatus;
use App\Enums\PaymentGateway;
use App\Enums\StorageProvider;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Interfaces\TransactionInterface;
use App\Models\Cart;
use App\Models\Course;
use App\Models\CourseBundle;
use App\Models\CourseBundleCartItem;
use App\Services\FileSystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class CourseBundleController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FileSystem $fileSystem)
    {
        return $this->success(
            collect(
                CourseBundle::published()
                    ->with(['courses', 'media'])
                    ->paginate(
                        page: $request->input('page') ?? 1,
                        perPage: $request->input('limit') ?? 20
                    )->items()
            )->map(fn (CourseBundle $courseBundle) => $this->format($courseBundle, $fileSystem))->toArray()
        );
    }

    private function format(CourseBundle $courseBundle, FileSystem $fileSystem): array
    {
        $courseBundle->loadMissing('media', 'courses');
        $courses = clone $courseBundle->courses;
        $media = $fileSystem->signUrl($courseBundle->media->first()->path, StorageProvider::S3PUBLIC);

        return collect($courseBundle->withoutRelations()->toArray())->merge([
            'image' => $media,
            'courses' => $courses
        ])->toArray();
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseBundle $courseBundle, FileSystem $fileSystem)
    {
        abort_if($courseBundle->status === CourseBundleStatus::DRAFT, Response::HTTP_NOT_FOUND);
        return $this->success($this->format($courseBundle->loadMissing(['courses', 'media']), $fileSystem));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'courseBundleId' => 'required|exists:course_bundles,id',
            'quantity' => 'sometimes|integer|min:1',
            'key' => 'sometimes|uuid',
            'tax' => 'sometimes|integer|min:0'
        ]);

        $courseBundle = CourseBundle::where('status', CourseBundleStatus::PUBLISHED)->findOrFail($request->courseBundleId);
        $cart = $this->cart($request);

        $courseBundle->cartItems()->updateOrCreate(
            ['cart_id' => $cart->id, 'course_bundle_id' => $courseBundle->id],
            ['quantity' => $request->quantity ?? 1, 'tax' => $request->tax ?? config('harde.tax')]
        );

        return $this->success([]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate(['courseBundleCartItemId' => 'required|exists:course_bundle_cart_items,id',]);
        $cart = $this->cart($request);
        $cart->courseBundleItems()
            ->getQuery()
            ->where('id', $request->courseBundleCartItemId)
            ->delete();

        return $this->success([]);
    }

    public function getCartItems(Request $request)
    {
        $user = $request->user('sanctum');
        $items = CourseBundleCartItem::with(['cart', 'courseBundle.courses'])->whereIn(
            'cart_id',
            fn ($query) => $query->select('id')
                ->from('carts')
                ->when($user, fn ($builder) => $builder->where('user_id', $user->id))
                ->when($request->key ?? false, fn ($builder) => $builder->where('key', $request->key))
        )->paginate($request->quantity ?? 15);

        return $this->success($items);
    }

    public function checkout(Request $request)
    {
        /** @var User */
        $user = $request->user('sanctum');
        $request->validate([
            'cartId' => 'required|exists:carts,id'
        ]);
        /** @var Cart */
        $cart = $request->user('sanctum')->carts()->with('courseBundleItems')->findOrFail($request->cartId);
        $transaction = $user->orderCourseBundleNow($cart)
            ->addCourseBundleItems($cart)
            ->issueInvoice(user: $user)
            ->recordTransaction(
                $user,
                $cart->courseBundleTotal(2, '.', '')
            );
        return $this->success(new TransactionResource($transaction),);
    }

    public function verifyPayment(Request $request, TransactionInterface $gateway)
    {
        $request->validate([
            'reference' => 'required',
            'cartId' => 'required|string|exists:carts,id',
            'provider' => ['required', new Enum(PaymentGateway::class)],
        ]);

        /** @var User */
        $user = $request->user('sanctum');
        DB::transaction(function () use ($user, $request, $gateway) {
            /** @var Cart */
            $cart = $user->carts()->one()->findOrFail($request->cartId);
            $transaction = $user->orderCourseBundleNow($cart)
                ->addCourseBundleItems($cart)
                ->issueInvoice(user: $user)
                ->recordTransaction(
                    $user,
                    $cart->courseBundleTotal(2, '.', '')
                );

            $transaction->update([
                'reference' => $request->reference,
                'payment_gateway' => $request->provider,
            ]);

            $gateway->verification(
                $transaction->refresh(),
                $request->reference
            );

            /** @var CourseBundleCartItem[] */
            $courseBundleItems = $cart->courseBundleItems()->getQuery()->with('courseBundle.courses')->get();
            foreach ($courseBundleItems as $item) {
                /** @var Course[] */
                $courses = $item->courseBundle->courses;
                foreach ($courses as $course) {
                    $course->enroll($user);
                }
            }
        });

        return $this->success([]);
    }

    private function cart(Request $request): Cart
    {
        $user = $request->user('sanctum');
        $key  = $request->key ?? Str::uuid()->toString();
        $attrs = array_merge([
            'name' => CartType::CART,
            'user_id' => $user->id,
        ], $request->key ? ['key' => $key] : []);

        return Cart::updateOrCreate($attrs, ['key' => $key]);
    }
}
