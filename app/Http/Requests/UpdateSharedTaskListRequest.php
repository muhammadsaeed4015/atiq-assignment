<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class UpdateSharedTaskListRequest extends FormRequest
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
            'permission' => [
                'required',
                Rule::in(['view', 'edit']), // Ensure it is either 'view' or 'edit'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'permission.required' => 'The permission field is required.',
            'permission.in' => 'The permission must be either view or edit.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(["error"=>$validator->errors()->first()], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
