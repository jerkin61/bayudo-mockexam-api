<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class QuestionFeedbackCreateRequest extends FormRequest
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
        'submitted_id' => 'required|integer',
        'question_id' => 'required|integer',
        'suggested_question' => 'required|string',
        'suggested_answer' => 'nullable|string',
        'user_feedback' => 'nullable|string',
        'suggested_explanation' => 'nullable|string',
        'suggested_choices' => 'required|string', // Validate that 'choices' is a string
        'question_id' => 'required|integer',
        'suggested_right_ans' => 'required|string',
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
            'question_no.required' => 'QuestionFeedback number is required',
            'question_no.integer' => 'QuestionFeedback number must be an integer',
            'question_no.unique' => 'A question with the same question number already exists',
            'question.required' => 'QuestionFeedback field is required',
            'question.string' => 'QuestionFeedback must be a string',
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
