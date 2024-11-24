<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class UpdateTaskRequest extends FormRequest
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
            'title' => 'string|max:100|required',
            'description' => 'string|max:350|required',
            'status' => [
                'required',
                Rule::in(['compeleted', 'incomplete']), 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'      => 'The title field is required.',
            'title.max'           => 'The title field may not be greater than 100 characters.',
            'description.required'   => 'The description field is required.',
            'description.max'        => 'The description field may not be greater than 350 characters.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either compeleted or incomplete.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(["error"=>$validator->errors()->first()], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
