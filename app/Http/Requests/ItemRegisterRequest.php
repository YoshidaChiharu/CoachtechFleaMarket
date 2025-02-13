<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ItemRegisterRequest extends FormRequest
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
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png,bmp',
                'extensions:jpeg,jpg,png,bmp',
                'max:10240'
            ],
            'categories' => ['required'],
            'condition_id' => ['required'],
            'name' => ['required', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'integer', 'min:300', 'max:9999999'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // 「商品の状態」未選択エラー
                if ($this->input('condition_id') < 1) {
                    $validator->errors()->add(
                        'condition_id',
                        '商品の状態を選択してください'
                    );
                }
            }
        ];
    }
}
