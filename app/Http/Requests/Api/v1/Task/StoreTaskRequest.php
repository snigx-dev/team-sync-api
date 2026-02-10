<?php

namespace App\Http\Requests\Api\v1\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:todo,in_progress,done'],
            'priority' => ['sometimes', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date', 'after:now'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
