<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        /*if (request()->has('organizerForm')) {
            return [
                'companyName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ];
        }*/

        return [
            'name' => 'required|string|max:255',
            'surname' => 'required_if:organizerForm,false|string|max:255',
            'companyName' => 'required_if:organizerForm,true|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Sprawdzanie unikalności w obu tabelach
                    $userExists = \App\Models\User::where('email', $value)->exists();
                    $organizerExists = \App\Models\Organizer::where('email', $value)->exists();

                    if ($userExists || $organizerExists) {
                        $fail('Adres e-mail już istnieje w systemie.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Pole imię jest wymagane.',
            'surname.required_if' => 'Pole nazwisko jest wymagane.',
            'companyName.required_if' => 'Pole nazwa firmy jest wymagane.',
            'email.required' => 'Pole adres e-mail jest wymagane.',
            'email.email' => 'Podaj poprawny adres e-mail.',
            'password.required' => 'Pole hasło jest wymagane.',
            'password.min' => 'Hasło musi mieć co najmniej :min znaków.',
            'password.confirmed' => 'Hasła muszą być zgodne.',
        ];
    }
}
