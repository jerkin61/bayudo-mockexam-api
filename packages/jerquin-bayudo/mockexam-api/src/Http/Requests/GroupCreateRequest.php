<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GroupCreateRequest extends FormRequest
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
// exams
// : 
// [5]
// group_code
// : 
// "123132"
// limitCount
// : 
// "1231"
// members
// : 
// [8]
// school
// : 
// "123123"
// user
// : 
// 8
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'exams' => 'required|array', // Exams must be an array
            'group_code' => 'required|string', // Each exam must have a group_code which is a string
            'limitCount' => 'required|integer', // Each exam must have a limitCount which is a string
            'members' => 'required|array', // Each exam must have a members array
            'school' => 'required|string', // Each member must have a school which is a string
            'user_id' => 'required|integer', // Each exam must have a user ID which is an integer
        ];
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'         => 'Name field is required',
            'name.string'           => 'Name is not a valid string',
            'name.max:255'          => 'Name can not be more than 255 character',
            'icon.string'           => 'icon is not a valid string',
            'image.string'          => 'image is not a valid image',
            'details.string'        => 'Details is not a valid string',
            'parent.integer'        => 'Parent is not a valid integer',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
