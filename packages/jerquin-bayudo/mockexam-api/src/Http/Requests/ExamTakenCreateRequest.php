<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ExamTakenCreateRequest extends FormRequest
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
// 	{
// 	"id": 1,
// 	"invoice_number": 1,
// 	"product_list": null,
// 	"total": "0.00",
// 	"discount_list": null,
// 	"status": "existing",
// 	"date_of_invoice": "2023-11-21",
// 	"name_of_cashier": null,
// 	"transaction_duration": "20:42:35",
// 	"payment_received": null,
// 	"change_tendered": null,
// 	"created_at": "2023-11-21T20:42:35.000000Z",
// 	"updated_at": "2023-11-21T20:42:35.000000Z"
// }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'user_id' => 'required|exists:users,id',
            'take' => 'nullable|boolean',
            'time_done' => 'nullable|date',
            'time_started' => 'nullable|date',
            'number_of_items' => 'nullable|integer|min:0',
            'pass' => 'nullable|boolean',
            'exam_result' => 'nullable|integer|min:0',
            'exam_id' => [
                'sometimes',
                'required',
                'exists:examlist,id',
                // Rule::unique('exam_taken')->where(function ($query) {
                //     return $query->where('exam_id', $this->exam_id);
                // }),
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
