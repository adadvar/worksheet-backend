<?php

namespace App\Http\Requests\Advert;

use App\Rules\CanChangeAdvertState;
use Illuminate\Foundation\Http\FormRequest;

class AdvertUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'title' => 'nullable|string|max:100',
            'slug' => 'nullable|string|unique:adverts,slug|max:10',
            'slug_url' => 'nullable|string|unique:adverts,slug_url|max:100',
            'info' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'price' => 'nullable|numeric|gt:0',
            'images' => 'nullable|array|max:1024',
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now',
            'state' => ['nullable', new CanChangeAdvertState($this->advert)],
            'content' => 'nullable|array'
        ];
    }
}
