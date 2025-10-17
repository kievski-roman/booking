<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'sometimes|string|max:255',
            'email'      => 'sometimes|email:rfc,dns|unique:users,email,' . $userId,
            'phone'      => 'sometimes|nullable|string|max:20',
            'password'   => 'nullable|string|min:8|confirmed',

        ];
    }
}
