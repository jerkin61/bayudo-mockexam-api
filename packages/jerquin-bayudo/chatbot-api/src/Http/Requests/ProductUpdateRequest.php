<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class ProductUpdateRequest extends FormRequest
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
            'name'        => ['string', 'max:255', 'sometimes'],
            'quantity'     => ['numeric','sometimes'],
            'sale_price'      => ['numeric','sometimes'],
            'sku'      => ['string','sometimes'],
            'tax'      => ['string','sometimes'],
            'unit'      => ['string','sometimes'],
            'wholesale_price'      => ['numeric','sometimes'],
            'status'      => ['string','sometimes'],
            'stack_size'      => ['numeric','sometimes'],
            'stack_label'      => ['string','sometimes'],
            'image'  => ['array', 'nullable'],
            'gallery'  => ['array', 'nullable'],
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
            'name.string'           => 'Name is not a valid string',
            'name.max:255'          => 'Name can not be more than 255 character',
            'image.string'          => 'image is not a valid string',
            'parent.integer'        => 'Parent is not a valid integer',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // TODO: Need to check from the request if it's coming from GraphQL API or not.
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
