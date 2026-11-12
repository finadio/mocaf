<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $articleId = $this->route('article');
        if (is_object($articleId)) {
            $articleId = $articleId->id;
        }

        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $articleId,
            'category' => 'required|string|max:100',
            'excerpt' => 'nullable|string|max:600',
            'content' => 'required|string',
            'thumbnail' => 'nullable', // string or uploaded file
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ];
    }
}

