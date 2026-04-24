<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:projects,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A project name is required.',
            'name.unique'   => 'A project with that name already exists.',
        ];
    }
}
