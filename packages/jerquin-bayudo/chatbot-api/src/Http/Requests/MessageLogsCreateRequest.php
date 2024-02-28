<?php


namespace Jerquin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class MessageLogsCreateRequest extends FormRequest
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
            'sender' => ['required', 'integer'],
            'reciever' => ['required', 'integer'],
            'message'  => ['string'],
            'seen' => ['integer'],
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
            'sender.required'         => 'Sender is required',
            'sender.integer'          => 'Sender is not a valid integer',
            'reciever.required'       => 'Reciever is required',
            'reciever.integer'        => 'Reciever is not a valid integer',
            'message.string'          => 'Message is not a valid string',
            'seen.integer'            => 'Seen is not a valid integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
