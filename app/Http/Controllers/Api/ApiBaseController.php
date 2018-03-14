<?php
namespace App\Http\Controllers\Api;

use App\Classes\RijndaelEncryption;
use App\Http\Controllers\Controller;
use App\Http\Traits\JWTUserTrait;
use Illuminate\Support\Facades\Request;

class ApiBaseController extends Controller {

	/**
	 * Extract token value from request
	 *
	 * @return string
	 */
	protected function extractToken($request=false) {
		return JWTUserTrait::extractToken($request);
	}

	/**
	 * Return User instance or false if not exist in DB
	 *
	 * @return mixed
	 */
	protected function getUserInstance($request=false) {
		return JWTUserTrait::getUserInstance($request);
	}

    protected function generateUserProfileResponse($user, $token=null)
    {
        $result = [
            'user_id'          =>  $user->id,
            'user_type'        =>  $user->userRoleKey,
            'first_name'       =>  $user->first_name,
            'last_name'        =>  $user->last_name,
            'email'            =>  $user->email,
            'phone'            =>  $user->phone,
            'profile_picture'  =>  $user->profile_picture_auto,
            'hospital_id'      =>  $user->hospital_id,
            'hospital_title'   =>  $user->hospital_title,
            'profession_id'    =>  $user->profession_id,
            'profession_title' =>  $user->profession_title,
        ];

        if ( $token ) {
            $result['_token'] = $token;
        } else {
            $result['_token'] = $this->extractToken();
        }

        return $result;
    }

}
