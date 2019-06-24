<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'firstName'=>'required|string|min:3',
            'lastName'=>'required|string|min:3',
            'email'=>'required|string|email|unique:admins',
            'password'=>'required|min:1|confirmed',
            'password_confirmation'=>'required',
            'phone'=>'required|numeric',
            'status'=>'required|in:1,2',
            'permission'=>'required|min:1',
            'address'=>'required|string',
            'image' => 'required|'.validateImage()
        ];
    }

    public function messages()
    {
        return
        [
            'firstName.required' => trans('admin.First name is required'),
            'lastName.required' => trans('admin.Last name is required'),
            'email.required' => trans('admin.E-mail is required and must be is E-mail'),
            'password.required' => trans('admin.Password is required must be 6 characters'),
            'password_confirmation.required' => trans('admin.Retype password is required and must be confirmation password'),
            'Phone.required' => trans('admin.Phone is required'),
            'status.required' => trans('admin.Status is required'),
            'address.required' => trans('admin.Address is required'),
            'permission.required' => trans('admin.Permission is required and select min one'),
            'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
        ];
    }
}
