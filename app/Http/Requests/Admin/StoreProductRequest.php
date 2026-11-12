<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'variant' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
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

