<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ExamCategoryTakenUpdateRequest extends FormRequest
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
  // "id": 1,
  // "exam_taken_id": 1,
  // "answered": 1,
  // "time_done": "2024-03-24 16:26:43",
  // "number_of_items": 234,
  // "pass": 0,
  // "exam_result": 120,
  // "exam_percentage": 0.2,
  // "exam_category_id": 3,
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'completed' => 'required|boolean',
            'exam_id' => 'sometimes|required|integer',
            'category_name' => 'sometimes|required|string',
            'items_count' => 'sometimes|required|integer',
            'time_limit' => 'sometimes|required|numeric',
            'time_limit_per_item' => 'sometimes|required|numeric',
            'instruction' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'exam_category_id' => [
            'sometimes',
            'required',
            'exists:examcategory,id',
            Rule::unique('exam_category_taken')->where(function ($query) {
                return $query->where('exam_category_id', $this->exam_category_id);
            }),
        ],
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
