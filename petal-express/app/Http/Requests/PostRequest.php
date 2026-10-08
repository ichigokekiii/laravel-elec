<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// shared rules for admin create and edit
class PostRequest extends FormRequest
{
    /**
     * Role middleware already blocks non-admins.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'price' => ['required', 'string', 'max:50', 'starts_with:₱'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please give the bouquet a name.',
            'body.required' => 'Please add a short description.',
            'price.required' => 'Please add a price.',
            'price.starts_with' => 'Price should start with ₱, like ₱1,299.',
            'category_id.required' => 'Please pick a category.',
            'category_id.exists' => 'That category is not in the list.',
        ];
    }
}
