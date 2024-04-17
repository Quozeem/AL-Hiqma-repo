<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
   return [
            //
            'name' => 'required|string',
            'isbn' => 'required|string',
            'authors' => 'required|array',
            'country' => 'required|string',
            'number_of_pages' => 'required|integer',
            'publisher' => 'required|string',
            

        ];
    }
}

