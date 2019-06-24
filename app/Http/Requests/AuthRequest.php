<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
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
            'email'=>'required|email|string',
            'password'=>'required|min:3',
            'remember' =>'sometime|nullable'
        ];
    }

    public function messages()
    {
        return
        [
            'email.required' =>trans('admin.This E-mail is required please fill this'),
            'password.required' =>trans('admin.This password is required please fill this')
        ];
    }
}
