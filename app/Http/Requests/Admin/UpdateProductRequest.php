<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $productId = $this->route('product');
        if (is_object($productId)) {
            $productId = $productId->id;
        }

        return [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId,
            'variant' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'main_image' => 'nullable', // can be uploaded file or existing string path
            'gallery.*' => 'nullable',
            'ingredients' => 'nullable|string',
            'nutrition_information' => 'nullable|string',
            'allergen_information' => 'nullable|string',
            'preparation_instructions' => 'nullable|string',
            'storage_instructions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ];
    }
}

