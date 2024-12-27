<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePostRequest extends FormRequest
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
            'title'            => ['bail', 'required', 'string'],
            'excerpt'          => ['bail', 'nullable', 'string'],
            'description'      => ['bail', 'nullable', 'string'],
            'image'            => ['bail', 'nullable', 'file'],
            'keywords'         => ['bail', 'nullable', 'string'],
            'meta_title'       => ['bail', 'required', 'string'],
            'meta_description' => ['bail', 'required', 'string'],
            'published_at'     => ['bail', 'required', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'title.required'            => 'The title is required.',
            'title.string'              => 'The title must be a string.',
            'excerpt.string'            => 'The excerpt must be a string.',
            'description.string'        => 'The description must be a string.',
            'image.file'              => 'The image must be a file.',
            'keywords.string'           => 'The keywords must be a string.',
            'meta_title.required'       => 'The meta title is required.',
            'meta_title.string'         => 'The meta title must be a string.',
            'meta_description.required' => 'The meta description is required.',
            'meta_description.string'   => 'The meta description must be a string.',
            'published_at.required'     => 'The published date is required.',
            'published_at.date'         => 'The published date must be a valid date.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
