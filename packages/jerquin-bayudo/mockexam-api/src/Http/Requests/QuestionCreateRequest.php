<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class QuestionCreateRequest extends FormRequest
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
        'question_no' => 'required|integer|unique:questions,question_no',
        'question' => 'required|string',
        'answer' => 'nullable|string',
        'choices' => 'required|string', // Validate that 'choices' is a string
        'exam_category_id' => 'required|integer',
        'time_left' => 'required|numeric', // Assuming it's a decimal
        'time' => 'nullable|numeric', // Assuming it's a decimal
        'answered' => 'nullable|boolean', // Validate that 'answered' is a boolean
        'right_ans' => 'required|string',
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
            'question_no.required' => 'Question number is required',
            'question_no.integer' => 'Question number must be an integer',
            'question_no.unique' => 'A question with the same question number already exists',
            'question.required' => 'Question field is required',
            'question.string' => 'Question must be a string',
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
