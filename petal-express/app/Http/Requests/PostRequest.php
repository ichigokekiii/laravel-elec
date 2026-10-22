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
        $imageRules = $this->isMethod('put') || $this->isMethod('patch')
            ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072']
            : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'];

        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'image' => $imageRules,
        ];
    }

    // admin types digits only; we store ₱1,299 in the database
    protected function prepareForValidation(): void
    {
        if (! $this->has('price')) {
            return;
        }

        $digits = preg_replace('/\D/', '', (string) $this->input('price'));

        if ($digits !== '') {
            $this->merge(['price' => (int) $digits]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): mixed
    {
        $data = parent::validated($key, $default);

        if ($key !== null) {
            return $data;
        }

        $data['price'] = '₱'.number_format((int) $data['price']);

        return $data;
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
            'price.integer' => 'Enter the price as a whole number (no ₱ sign).',
            'price.min' => 'Price cannot be negative.',
            'category_id.required' => 'Please pick a category.',
            'category_id.exists' => 'That category is not in the list.',
            'image.required' => 'Please upload a photo for this bouquet.',
            'image.image' => 'The file must be a photo.',
            'image.mimes' => 'Use JPG, PNG, or WebP.',
            'image.max' => 'The photo must be 3 MB or smaller.',
        ];
    }
}
