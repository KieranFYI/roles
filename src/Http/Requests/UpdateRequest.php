<?php

namespace KieranFYI\Roles\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use KieranFYI\Roles\Core\Models\Roles\Role;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'roles' => ['required', 'array'],
            'roles.*' => ['integer', Rule::exists(Role::class, 'id')]
        ];
    }
}
