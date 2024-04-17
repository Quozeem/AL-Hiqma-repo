<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;


class ExternalBookController extends Controller
{
    public function getExternalBooks(Request $request)
    {
    return Book::getExternalBooks($request);
      
    }

    public function index()
    {
    return Book::index();
      
    }
    
    public function create(Request $request)
{
    
    return Book::create_book($request);
    }
    
    public function show($id)
    {
    return Book::show($id);       
    }

    public function updates(Request $request)
    {
    return Book::update_book($request);
    }
    
    public function destroy($id)
    {
    return Book::destroy($id);
        
    }
}
