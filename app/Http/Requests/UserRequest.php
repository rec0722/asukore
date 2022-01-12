<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'password' => 'required|confirmed',
      'password_confirmation' => 'required',
    ];
  }

  /**
   * 定義済みバリデーションルールのエラーメッセージ取得
   *
   * @return array
   */
  public function messages()
  {
    return [
      'password.confirmed' => 'パスワードが一致しません',
    ];
  }
}
