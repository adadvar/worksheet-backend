<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use App\Rules\UploadedBannerWorksheetId;
use App\Rules\UploadedFileWorksheetId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\In;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'total_price' => 'nullable|numeric',
            'status' => ['required' ,new In(Order::TYPES)],
        ];
    }
}
