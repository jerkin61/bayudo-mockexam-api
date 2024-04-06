<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class QuestionFeedbackUpdateRequest extends FormRequest
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
    //   name: values.name,
    //   number_of_items: values.number_of_items,
    //   instruction: values.instruction,
    //   description: values.description,
    //   passing_rate: values.passing_rate,
    //   total_time_limit_hours: values.total_time_limit_hours,
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
public function rules()
{
    return [
        'submitted_id' => 'required|integer',
        'suggested_question' => 'sometimes|string',
        'suggested_answer' => 'sometimes|nullable|string',
        'user_feedback' => 'sometimes|nullable|string',
        'suggested_explanation' => 'sometimes|nullable|string',
        'suggested_choices' => 'sometimes|string', // Validate that 'choices' is a string
        'question_id' => 'sometimes|integer',
        'suggested_right_ans' => 'sometimes|string',
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
