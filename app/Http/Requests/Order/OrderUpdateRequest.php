<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->order);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'total_price' => 'nullable|numeric',
            'status' => 'sometimes|required|in:' . implode(',', Order::TYPES),
            'items' => 'sometimes|required|array',
            'items.*.id' => 'sometimes|required|exists:order_items,id',
            'items.*.worksheet_id' => 'sometimes|required|exists:worksheets,id',
            'items.*.quantity' => 'nullable|integer',
            'items.*.price' => 'nullable|numeric',
        ];
    }
}
