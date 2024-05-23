<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;

class StorePostRequest extends FormRequest
{
    public static array $extensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        'mp3', 'mp4', 'wav',
        'doc', 'docx', 'pdf', 'csv', 'xls', 'xlsx', 'ppt',
        'zip', 'rar',
    ];
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
            'attachments' => [
                'array',
                'max:50',
                function($attribute, $value, $fail) {
                    $totalSize = collect($value)->sum(fn(UploadedFile $file) => $file->getSize());

                    if ($totalSize > (1 * 1024 * 1024 * 1024)) {
                        $fail('The total size of all attachments may not exceed 1GB.');
                    }
                },
            ],
            'attachments.*' => [
                'file',
                File::types(self::$extensions)->max('1gb')
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

    public function messages(): array
    {
        return [
            'attachments.*.file' => 'This attachment must be a file.',
            'attachments.*.max' => 'This attachment may not be greater than 1GB.',
            'attachments.*.mimes' => 'Invalid file type.'
        ];
    }

//    protected function failedValidation(Validator $validator)
//    {
//        $errors = (new ValidationException($validator))->errors();
//        if ($this->expectsJson()) {
//            throw new HttpResponseException(
//                response()->json(['errors' => $errors], 422)
//            );
//        } else {
//            throw new HttpResponseException(
//                redirect()->back()
//                    ->withErrors($errors)
//            );
//        }
//    }
}
