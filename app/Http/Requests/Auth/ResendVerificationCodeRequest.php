<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResendVerificationCodeRequest extends FormRequest
{
    use GetRegisterFieldAndValueTrait;
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
            'mobile' => ['required_without:email', new MobileRule],
            'email' => 'required_without:mobile|email',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Get the first error message
        $firstErrorMessage = $validator->errors()->first();

        // Throw an exception with the first error message
        throw new HttpResponseException(response()->json([
            'message' => $firstErrorMessage,
        ], 422));
    }
}
