<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

//class UpdatePostRequest extends StorePostRequest
class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Todo maybe change later

        //        $post = Post::where('id', '=', $this->input('id'))
        //            ->where('user_id', '=', Auth::id())
        //            ->first();
        //
        //        return !!$post;

        $post = $this->route('post');

        return $post->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //        return array_merge(parent::rules(), [
        //            'deleted_files_ids' => ['array', 'max:20'],
        //            'deleted_files_ids.*' => 'numeric'
        //        ]);
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
            ],
            'deleted_files_ids' => ['array', 'max:20'],
            'deleted_files_ids.*' => 'numeric'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'body' => $this->input('body') ?: ''
        ]);
    }
}
