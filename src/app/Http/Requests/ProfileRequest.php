<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'max:20'],
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
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
