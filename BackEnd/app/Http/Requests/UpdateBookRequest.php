<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'name' => 'sometimes|required|string',
            'isbn' => 'sometimes|required|string',
            'authors' => 'sometimes|required|array',
            'country' => 'sometimes|required|string',
            'number_of_pages' => 'sometimes|required|integer',
            'publisher' => 'sometimes|required|string',
            'release_date' => 'sometimes|required|date',
        ];
    }
}
