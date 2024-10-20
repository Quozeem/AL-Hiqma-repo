<?php

namespace App\Http\Requests\Hakim;

use App\Enums\DocumentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


/**
 * @OA\Schema(schema="AssessmentRequest")
 * {
 *
 *   @OA\Property(
 *     property="question",
 *     type="string",
 *     description="The assessment question."
 *   ),
 * }
 */
class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'program_id' => ['required','numeric','exists:programs,id'],
          'section_id' => ['required','numeric','exists:sections,id'],
            'session_id' => ['required','numeric','exists:sessions,id'],
            'semester_id' => ['required','numeric','exists:semesters,id'],
          
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
