<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CreateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Requests\UpdateBookRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'name',
        'isbn',
        'authors',
        'country',
        'number_of_pages',
        'publisher',
        
    ];

    


    public static function getExternalBooks(Request $request)
    {
        $nameOfABook = $request->query('name');

        $response = Http::get("https://www.anapioficeandfire.com/api/books?name=$nameOfABook");

        $data = $response->json();

        if (empty($data)) {
            return response()->json([
                'data' => [],
                'status' => 'success',
                'status_code' => 200
            ]);
        }

        $formattedData = [];
        foreach ($data as $book) {
            $formattedData[] = [
                'name' => $book['name'],
                'isbn' => $book['isbn'],                                                                                                                                                                                                                                                                                                 'authors' => $book['authors'],
                'number_of_pages' => $book['numberOfPages'],
                'publisher' => $book['publisher'],
                'country' => $book['country'],
                'release_date' => $book['released'],
            ];
        }

        return response()->json([
            'data' => $formattedData,
            'status' => 'success',
            'status_code' => 200
        ]);
    }

    public static function index()
    {
        $books = Self::all();

        if (empty($books)) {
            return response()->json([
                'data' => [],
                'status' => 'success',
                'status_code' => 200
            ]);
        }

        $formattedBooks = [];
        foreach ($books as $book) {
            $formattedBooks[] = [
                'id' => $book->id,
                'name' => $book->name,
                'isbn' => $book->isbn,
                'authors' => $book->authors,
                'country' => $book->country,
                'number_of_pages' => $book->number_of_pages,
                'publisher' => $book->publisher,
                'release_date' => $book->release_date,
            ];
        }
        return response()->json([
            'data' => $formattedBooks,
            'status' => 'success',
            'status_code' => 200
        ]);
    }
    public static function create_book( $request)
    {
        $book = Self::create($request->all());
    
        return response()->json([
            'message' => "The book ".$request->name." was created successfully",
            'data' => $book,
            'status' => 'success',
            'status_code' => 201
        ], 201);
    }


    public static function update_book( $request)
    {
        $book = Self::findOrFail($request->id);
        $book->update($request->all());
    
        return response()->json([
            'message' => "The book {$book->name} was updated successfully",
            'data' => $book,
            'status' => 'success',
            'status_code' => 200
        ]);
    }
    
    public static function show($id)
    {
        $book = Self::findOrFail($id);

        return response()->json([
            'data' => $book,
            'status' => 'success',
            'status_code' => 200
        ]);
    }

    public static function destroy($id)
    {
        $book = Self::findOrFail($id);

        $book->delete();

        return response()->json([
            'message' => "The book {$book->name} was deleted successfully",
            'data' => [],
            'status' => 'success',
            'status_code' => 204
        ]);
    }
}
