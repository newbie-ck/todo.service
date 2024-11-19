<?php

namespace App\Http\Requests;

use App\Constants\RegexConstants;
use Illuminate\Foundation\Http\FormRequest;

class TodoCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'todo_title' => ['required', 'string', 'max:120', 'regex:' . RegexConstants::INJECTION_PROTECTED_WITH_SYMBOLS],
        ];
    }
}