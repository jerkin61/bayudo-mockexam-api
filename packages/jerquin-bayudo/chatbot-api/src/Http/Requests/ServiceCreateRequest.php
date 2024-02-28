<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ServiceCreateRequest extends FormRequest
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
//  $columnsToSelect = ['name', 'type_of_unit', 'industry', 'basis_of_pay', 'description', 'shop_id', 'service_fee', 'status', 'duration_minutes','attachment','features', 'image', 'gallery', 'location'];
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
 public function rules()
{
    return [
        'name'              => ['required', 'string', 'max:255'],
        'slug'              => ['string'],
        'type_of_unit'      => ['string', 'nullable'],
        'industry'          => ['string', 'nullable'],
        'basis_of_pay'      => ['string', 'nullable'],
        'description'       => ['string', 'nullable'],
        'shop_id'           => ['integer', 'nullable', 'exists:shop,id'],
        'service_fee'       => ['numeric', 'nullable'],
        'status'            => ['string', Rule::in(['active', 'inactive'])],
        'duration_minutes'  => ['integer', 'nullable'],
        'attachment'        => ['json', 'nullable'],
        'features'          => ['json', 'nullable'],
        'image'             => ['json', 'nullable'],
        'gallery'           => ['json', 'nullable'],
        'location'          => ['string', 'nullable'],
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
