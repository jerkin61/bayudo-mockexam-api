<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductCreateRequest extends FormRequest
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
			// "name": "Perfect Skin",
			// "slug": "test_slug",
			// "description": "Test description",
			// "price": 200,
			// "shop_id": 1,
			// "sale_price": 2000,
			// "sku": "123123",
			// "quantity": 0,
			// "in_stock": 1,
			// "is_taxable": 0,
			// "status": "publish",
			// "height": null,
			// "width": null,
			// "length": null,
			// "image": null,
			// "gallery": null,
			// "deleted_at": null,
			// "created_at": null,
			// "updated_at": null,
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'slug'      => ['string'],
            'shop_id' => ['integer', 'nullable', 'exists:Jerquin\Database\Models\Shop,id'],
            'description' => ['string'],
            'price'  => ['integer', 'nullable'],
            'sale_price'  => ['numeric', 'nullable'],
            'logo'  => ['string', 'nullable'],
            'sku'  => ['numeric', 'required'],
            'quantity'  => ['integer', 'nullable'],
            'is_taxable'  => ['integer', 'nullable'],
            'status'  => ['string',  Rule::in(['publish', 'draft'])],
            'height'  => ['integer', 'nullable'],
            'width'  => ['integer', 'nullable'],
            'length'  => ['integer', 'nullable'],
            'image'  => ['array', 'nullable'],
            'gallery'  => ['array', 'nullable'],
            'unit'  => ['string', 'required'],
            'wholesale_price'  => ['numeric', 'required'],           
            'stack_size'      => ['numeric','nullable'],
            'stack_label'      => ['string','nullable'],
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
