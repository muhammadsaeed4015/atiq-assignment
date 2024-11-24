<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CreateTaskListRequest extends FormRequest
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
            'task_ids'    => 'array',
            'task_ids.*'  => [
                'integer',
                Rule::exists('tasks', 'id') // Checks if each task_id exists in the 'tasks' table's 'id' column.
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
            'task_ids.array'         => 'The task_ids field must be an array.',
            'task_ids.*.integer'     => 'Each task ID must be an integer.',
            'task_ids.*.exists'      => 'One or more task IDs are invalid.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(["error"=>$validator->errors()->first()], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
