<?php

namespace App\Http\Requests;

use App\Constants\RegexConstants;
use Illuminate\Foundation\Http\FormRequest;

class TodoListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'todo_title' => ['nullable', 'string', 'max:120', 'regex:' . RegexConstants::INJECTION_PROTECTED_WITH_SYMBOLS],
            'is_completed' => ['nullable', 'integer', 'between:-1,1'],
            'sortBy' => ['nullable', 'string', 'regex:' . RegexConstants::INJECTION_PROTECTED_WITH_SYMBOLS],
            'orderBy' => ['nullable', 'string', 'in:asc,desc', 'regex:' . RegexConstants::INJECTION_PROTECTED_WITH_SYMBOLS],
        ];
    }
}