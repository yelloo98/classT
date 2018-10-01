<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{

	public function authorize()
	{
		return true;
	}

    public function rules()
    {
        return [
	        'email' => 'required|email',
	        'password' => 'required',
        ];
    }


    public function messages() {
	    return [
		    'email.required' => '이메일은 필수입니다.',
		    'password.required' => '비밀번호는 필수입니다.',
	    ];
    }
}
