<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterRequest extends Request
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
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'first_name' => 'max:255',
            'second_name' => 'max:255',
            'password' => 'required|min:6'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
            'password.min' => 'The field password must contain not less than 6 characters',
            'email.email' => 'The field email must have format as an e-mail address',
            'unique' => 'User with this :attribute already exists'
        ];
    }

    /**
     * Override default response by always responding with JSON
     *
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $result = [];
        $result['status'] = 422;
        $result['data'] = false;
        $result['error'] = $errors;
        return response()->json($result, $result['status']);
    }
}
