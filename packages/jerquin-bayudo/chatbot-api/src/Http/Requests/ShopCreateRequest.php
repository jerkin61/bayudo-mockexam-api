<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class ShopCreateRequest extends FormRequest
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
            // $table->unsignedBigInteger('owner_id');
            // $table->foreign('owner_id')->references('id')->on('users');
            // $table->string('company_name')->nullable();
            // $table->string('slug')->nullable();
            // $table->text('description')->nullable();
            // $table->text('business_type')->nullable();
            // $table->json('cover_image')->nullable();
            // $table->json('logo')->nullable();
            // $table->boolean('is_active')->default(false);
            // $table->json('address')->nullable();
            // $table->json('settings')->nullable();
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_name'      => ['required', 'string', 'max:255'],
            'slug'      => ['string'],
            'shop_id' => ['integer'],
            'description' => ['string'],
            'shop_type'  => ['integer', 'nullable'],
            'cover_image'  => ['string', 'nullable'],
            'logo'  => ['string', 'nullable'],
            'is_active'  => ['integer', 'nullable'],
            'address'  => ['integer', 'nullable'],
            'settings'  => ['integer', 'nullable'],
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
