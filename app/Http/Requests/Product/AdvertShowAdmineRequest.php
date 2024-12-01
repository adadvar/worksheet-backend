<?php

namespace App\Http\Requests\Advert;

use App\Models\Advert;
use App\Rules\CanChangeAdvertState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AdvertShowAdmineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $advert = Advert::first();
        return Gate::allows('show-admin', $advert);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [];
    }
}
