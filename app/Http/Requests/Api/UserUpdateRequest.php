<?php
namespace App\Http\Requests\Api;

use App\Classes\RijndaelEncryption;
use App\Http\Requests\Jsonify as Request;
use App\Http\Traits\JWTUserTrait;
use App\Models\User;

class UserUpdateRequest extends Request {

    use JWTUserTrait;

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
            'email.unique'      => 'Email already found in our system, please try another one.',
            'phone.phone'       => 'Please enter your valid phone number in international format.',
            'old_pwd.different' => 'The old password and new password must be different.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = self::getUserInstance();

        $rules = [
            'first_name'  => 'string',
            'last_name'  => 'string',
            'email'      => 'email|max:255|unique_encrypted:users,email,'.$user->id.',id',
            // 'username'   => 'min:3|unique:users,username,'.$user->id.',id',
            'password'   => 'min:6',
            'old_pwd'    => 'required_with:password|different:password',
            'city'       => 'integer',
            'state'      => 'integer',
            'phone'      => 'phone:US|unique_encrypted:users,phone,'.$user->id.',id',
        ];

        return $rules;
    }

    public function all()
    {
        $data = parent::all();

        // This step is to avoid repetition, when $request->all() is being called from controller
        // Need to find alternate way of merging data.
        if ( array_key_exists(';modified;', $data) ) {
            return $data;
        }

        if ( array_key_exists('phone', $data) ) {
            // First decrypt the phone
            $data['phone'] = RijndaelEncryption::decrypt($data['phone']);
            $data['phone'] = sprintf('+1%s', ltrim($data['phone'], '+1'));
        }

        $encryptFields = collect(User::getEncryptionFields())
            ->exclude([
                'phone'
            ]);

        foreach ($encryptFields as $field) {
            $data[$field] = RijndaelEncryption::decrypt($data[$field]);
        }

        $data[';modified;'] = true;

        $this->merge($data); // This is required since without merging, it doesn't pass modified value to controller.

        return $data;
    }
}
