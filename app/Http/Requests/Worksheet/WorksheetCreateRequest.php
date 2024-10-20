<?php

namespace App\Http\Requests\Worksheet;

use App\Models\Worksheet;
use App\Rules\UploadedBannerWorksheetId;
use App\Rules\UploadedFileWorksheetId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class   WorksheetCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO محدودیت ایجاد که اگهی برای خود یوز یا ادمین باشد.
        return Gate::allows('create', Worksheet::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'grade_id' => 'nullable|exists:categories,id',
            'subject_id' => 'nullable|exists:categories,id',
            'topic_id' => 'nullable|exists:categories,id',       
            'name' => 'nullable|string|max:100',
            'slug' => 'nullable|string|unique:worksheets,slug|max:100',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|gt:0',
            'banner' => ['nullable', new UploadedBannerWorksheetId()],
            'file' => ['nullable', new UploadedFileWorksheetId()],
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now',
        ];
    }
}
