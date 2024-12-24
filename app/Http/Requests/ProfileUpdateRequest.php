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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'file',
                'image',
                'mimes:jpeg,jpg,png,bmp',
                'extensions:jpeg,jpg,png,bmp',
                'max:10240'
            ],
            'name' => ['required', 'string', 'max:255'],
            'postcode' => ['nullable', 'regex:/^[0-9]+$/', 'digits:7'],
            'address' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }
}
