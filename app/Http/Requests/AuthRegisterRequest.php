<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    private $table            = 'users';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => "bail|required|unique:$this->table,username",
            'fullname' => 'bail|required',
            'phone'     => 'bail|required|max:10',
            'email'    => 'bail|required|email',
            'password' => 'bail|required_with:confirm_password|same:confirm_password|min:6',
            'confirm_password' => 'min:6'      // thường, in hoa, số, ký tự đặt biệt
            // đổi pass: 
            // register
        ];
    }

    public function messages()
    {
        return [
            // 'name.required' => 'Name không được rỗng',
            // 'name.min'      => 'Name :input chiều dài phải có ít nhất :min ký tứ',
        ];
    }
    public function attributes()
    {
        return [
            // 'description' => 'Field Description: ',
        ];
    }
}
