<?php
namespace App\Http\Requests\Api;

use App\Http\Requests\Jsonify as Request;

class UserRegisterRequest extends Request {

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
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique'    => 'Email already found in our system, please try another one.',
            'phone.phone'    => 'Please enter your valid phone number.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string',
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'profession_id' => 'required|min:1',
            'hospital_id'   => 'required|min:1',
            'phone'         => 'required|phone:US|unique_phone:US,users,phone',
        ];

        return $rules;
    }

    public function all()
    {
        $data = parent::all();

        if ( array_key_exists('phone', $data) ) {
            $data['phone'] = sprintf('+1%s', ltrim($data['phone'], '+1'));
        }

        $this->merge($data); // This is required since without merging, it doesn't pass modified value to controller.

        return $data;
    }
}
