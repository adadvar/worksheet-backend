<?php

namespace App\Http\Requests\Worksheet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class WorksheetDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::forUser(auth('api')->user())
            ->allows('download', $this->worksheet);
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
