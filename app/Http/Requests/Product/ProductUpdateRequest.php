<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Rules\UploadedBannerProductId;
use App\Rules\UploadedFileProductId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\In;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->product);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['nullable', new In(Product::TYPES)],
            'grade_id' => 'nullable|exists:categories,id',
            'subject_id' => 'nullable|exists:categories,id',
            'topic_id' => 'nullable|exists:categories,id',
            'name' => 'nullable|string|max:100',
            'slug' => 'nullable|string|unique:products,slug|max:100',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|gt:0',
            'banner' => ['nullable', new UploadedBannerProductId()],
            'file' => ['nullable', new UploadedFileProductId()],
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now',
        ];
    }
}
