<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InvoiceUpdateRequest extends FormRequest
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
            'invoice_number'      => ['required', 'numeric','sometimes'],
            'product_list'        => ['json','sometimes'],
            'total'               => ['numeric', 'nullable','sometimes'],
            'discount_list'       => ['json','sometimes'],
            'status'              => ['string', Rule::in(['existing', 'cancelled', 'completed', 'hold']),'sometimes'],
            'date_of_invoice'     => ['date', 'nullable','sometimes'],
            'name_of_cashier'     => ['string', 'nullable','sometimes'],
            'transaction_duration' => ['regex:/^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/', 'nullable','sometimes'],
            'payment_received'    => ['numeric', 'nullable','sometimes'],
            'change_tendered'     => ['numeric', 'nullable','sometimes'],   
            'notes'     => ['string', 'nullable'],
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
