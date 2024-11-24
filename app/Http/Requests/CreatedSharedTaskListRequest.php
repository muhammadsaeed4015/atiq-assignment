<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TaskList;
use App\Models\SharedTaskList;


class CreatedSharedTaskListRequest extends FormRequest
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
            'shared_with' => [
                'required',
                'integer',
                Rule::exists('users', 'id'), // Ensure the user exists in the users table
                'not_in:' . auth()->id(),
                function ($attribute, $value, $fail) {
                    if ($this->isTaskListAlreadyShared($value, $this->task_list_id)) {
                        $fail('This task list is already shared with the selected user.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (!$this->isTaskListOwned($this->task_list_id)) {
                        $fail('This task list is not yours');
                    }
                }
            ],
            'task_list_id' => [
                'required',
                'integer',
                Rule::exists('task_lists', 'id'), // Ensure the task exists in the tasks table
            ],
            'permission' => [
                'required',
                Rule::in(['view', 'edit']), // Ensure it is either 'view' or 'edit'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'shared_with.required' => 'The user to share with is required.',
            'shared_with.integer' => 'The shared_with field must be a valid user ID.',
            'shared_with.exists' => 'The selected user does not exist.',
            'shared_with.not_in' => 'You cannot share a task list with yourself.',
            'task_list_id.required' => 'The task List ID is required.',
            'task_list_id.integer' => 'The task List ID must be an integer.',
            'task_list_id.exists' => 'The selected task list does not exist.',
            'permission.required' => 'The permission field is required.',
            'permission.in' => 'The permission must be either view or edit.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(["error"=>$validator->errors()->first()], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    private function isTaskListAlreadyShared($sharedWith, $taskId): bool
    {
        return SharedTaskList::
            where('shared_with', $sharedWith)
            ->where('task_list_id', $taskId)
            ->exists();
    }

    private function isTaskListOwned($taskId): bool
    {
        return TaskList::
            where('created_by', auth()->id())
            ->where('id', $taskId)
            ->exists();
    }
}
