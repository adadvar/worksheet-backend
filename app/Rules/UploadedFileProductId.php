<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;

class UploadedFileProductId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $isFileExist =  Storage::exists('products/tmp/' . $value);
        $isFileExist = Storage::disk('products')->exists('tmp/' . $value);

        if (!$isFileExist) {
            $fail('فایل با نام :attribute وجود ندارد.');
        }
    }
}
