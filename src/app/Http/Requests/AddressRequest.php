<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * 認可
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules()
    {
        return [
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['nullable'],
        ];
    }

    /**
     * バリデーションメッセージ
     */
    public function messages()
    {
        return [
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
