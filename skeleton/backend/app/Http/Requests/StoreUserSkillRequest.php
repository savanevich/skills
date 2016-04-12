<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\Request;

class StoreUserSkillRequest extends Request
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
            'name' => 'required',
            'pivot.level' => 'required|between:1,10',
            'category_id' => 'required|exists:categories,id',
            'pivot.id' => 'required|exists:user_technology,id'
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
            'name.required' => 'The field name is required.',
            'pivot.level.required' => 'Level is required',
            'pivot.level.between' => 'Level should be between 1 and 10'
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
