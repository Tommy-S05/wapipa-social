<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string'],
            'attachments' => ['array', 'max:20'],
            'attachments.*' => [
                'file',
                File::types([
                    'jpg', 'jpeg', 'png', 'gif', 'webp',
                    'mp3', 'mp4', 'wav',
                    'doc', 'docx', 'pdf', 'csv', 'xls', 'xlsx', 'ppt',
                    'zip', 'rar',
                ])->max('500mb')
            ]
            //            'user_id' => ['numeric']
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
           'body' => $this->input('body') ?: ''
        ]);
    }

//    protected function passedValidation(): void
//    {
//        $this->merge(['user_id' => auth()->user()->id]);
//    }
}
