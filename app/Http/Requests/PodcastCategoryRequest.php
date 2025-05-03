<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PodcastCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_ids'   => 'array',
            'category_ids.*' => 'exists:categories,id',
            'category_names' => 'array',
            'category_names.*' => 'string|min:1|max:255'
        ];
    }
}
