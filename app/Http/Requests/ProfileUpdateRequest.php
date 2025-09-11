<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'grade' => ['nullable', 'integer', 'min:1', 'max:6'],
            'group' => ['nullable', 'in:A,B,C,D'],
            'major' => ['nullable', 'in:IE,ISC,IIA,II,ISA,IIAS,IGE'],
            // ...otros campos...
        ];
    }
}
