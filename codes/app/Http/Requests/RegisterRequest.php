<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile_number' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'nid' => ['required', 'regex:/(?:\d{17}|\d{13}|\d{10})/', 'unique:'.User::class],
            'vaccine_center_id' => ['required', 'exists:'.VaccineCenter::class.',id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
