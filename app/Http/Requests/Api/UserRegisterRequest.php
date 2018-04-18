<?php
namespace App\Http\Requests\Api;

use App\Classes\RijndaelEncryption;
use App\Http\Requests\Jsonify as Request;
use App\Models\User;

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
            'password'      => 'required|string|min:6',
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'profession_id' => 'required|exists:professions,id|min:1',
            'hospital_id'   => 'required|exists:hospitals,id|min:1',
          //  'phone'         => 'required|phone:US|unique_encrypted:users,phone',
            'phone'         => 'required|unique:users,phone',
        ];

        return $rules;
    }

    public function all()
    {
        $data = parent::all();

//        // This step is to avoid repetition, when $request->all() is being called from controller
//        // Need to find alternate way of merging data.
//        if ( array_key_exists(';modified;', $data) ) {
//            return $data;
//        }
//
//        if ( array_key_exists('phone', $data) ) {
//            // First decrypt the phone
//            $data['phone'] = RijndaelEncryption::decrypt($data['phone']);
//            $data['phone'] = sprintf('+1%s', ltrim($data['phone'], '+1'));
//        }
//
//        foreach (collect(User::getEncryptionFields())->exclude(['phone']) as $field) {
//            $data[$field] = RijndaelEncryption::decrypt($data[$field]);
//        }
//
//        $data[';modified;'] = true;
//
//        $this->merge($data); // This is required since without merging, it doesn't pass modified value to controller.

        return $data;
    }
}
