<?php
namespace App\Http\Controllers\Api;

use App\Classes\Email;
use App\Events\Api\JWTUserLogin;
use App\Events\Api\JWTUserLogout;
use App\Events\Api\JWTUserRegistration;
use App\Events\Api\JWTUserUpdate;
use App\Events\Api\NotificationsListed;
use App\Events\UserPasswordChanged;
use App\Helpers\RESTAPIHelper;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\City;
use App\Models\Hospital;
use App\Models\Profession;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Config;
use Exception;
use Illuminate\Http\Request;
use JWTAuth;
use Propaganistas\LaravelPhone\PhoneNumber;
use Validator;

class WebserviceController extends ApiBaseController {

    public function __construct()
    {
        // Set rate limiter (max 1 hit in 5 minutes)
        if ( app()->environment('production') ) {
            $this->middleware('jwt.throttle:2,5,emailVerification', ['only' => ['resendVerificationEmail']]);
            $this->middleware('jwt.throttle:2,5,resetPassword', ['only' => ['resetPassword']]);
        }
    }

    public function initializationConfigs(Request $request)
    {
        $allInOneConfigs = [];
        $allInOneConfigs['professions'] = Profession::active()->pluck('title', 'id');
        $allInOneConfigs['hospitals'] = Hospital::active()->pluck('title', 'id');

        return RESTAPIHelper::response( $allInOneConfigs );
    }

    public function register(UserRegisterRequest $request)
    {
        $input                       = $request->all();
        $input['password']           = bcrypt($input['password']);
        $input['active']             = 0;
        $input['email_verification'] = 1;
        $input['role_id']            = User::ROLE_PHYSICIANS;

        if ( $request->has('phone') ) {
            try {
                $input['phone'] = phone($request->get('phone'), 'US')->formatE164();
            } catch (\Exception $e) {
                $input['phone'] = '';
            }
        }

        // So split name is not required here.
        // list($input['first_name'], $input['last_name']) = str_split_name($input['full_name']);

        if ( $request->hasFile('profile_picture') ) {
            $imageName = \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = public_path( config('constants.front.dir.profilePicPath') );
            $request->file('profile_picture')->move($path, $imageName);

            if ( Image::open( $path . '/' . $imageName )->scaleResize(200, 200)->save( $path . '/' . $imageName ) ) {
                $input['profile_picture'] = $imageName;
            }
        }

        $user = User::create($input);
        $user = User::find($user->id); // Just because we need complete model attributes for event based activities

        $user->email_verification = 1;
        $user->save();

        // Fire user registration event
        event(new JWTUserRegistration($user));

        if ( $user->email_verification != 1 ) {
            return RESTAPIHelper::response([], true, 'Your account has been registered and email address requires verification. A verification code is sent to your email. Please also check Junk/Spam folder as well.');
        } else {
            return $this->login($request);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'        => 'required|email',
            'password'     => 'required',
            'device_type'  => 'in:ios,android',
            'device_token' => 'string',
        ]);

        if ($validator->fails()) {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        // Login with email OR username supported!
        if ( valid_email($request->get('email')) ) {
            $input = $request->only(['email', 'password']);
        } else {
            $request->merge(['username' => $request->get('email')]);
            $input = $request->only(['username', 'password']);
        }

        // Allow only following role
        $input['role_id'] = User::ROLE_PHYSICIANS;

        if (!$token = JWTAuth::attempt($input)) {
            return RESTAPIHelper::response('Invalid credentials, please try-again.', false);
        }

        $userData = JWTAuth::toUser($token);

        // LOW | TODO: Check from constants if enable single device login

        /* Do your additional/manual validation here like email verification or enable/disable */
        try {
            $userData->validateUserActiveCriteria();
        } catch (\App\Exceptions\UserNotAllowedToLogin $e) {
            return RESTAPIHelper::response($e->getMessage(), false, $e->getResolvedErrorCode());
        }

        if ( constants('api.config.allowSingleDeviceLogin') ) {
            $userData->removeDevice( $token );
        }

        // Add user device
        $userData->addDevice( $request->get('device_token', ''), $request->get('device_type', null), $token );

        // Generate user response
        $result = $this->generateUserProfileResponse( $userData, $token );

        event(new JWTUserLogin($userData));

        return RESTAPIHelper::response( $result );
    }

    public function logout(Request $request)
    {
        try {
            $me = $this->getUserInstance();

            if ( $me ) {
                $me->removeDevice( $this->extractToken() );

                // Fire user logout event
                event(new JWTUserLogout($me, [
                    'sendLogoutPush' => false,
                ]));
            }

            JWTAuth::invalidate( $this->extractToken() );

        } catch (Exception $e) {}

        return RESTAPIHelper::emptyResponse();
    }

    public function resetPassword(Request $request) {
        $response = \Password::broker()->sendResetLink($request->only('email'));

        switch ($response) {
            case \Password::INVALID_USER:
                return RESTAPIHelper::response('Email not found in the system.', false, 'invalid_email');
                break;
            case \Password::RESET_LINK_SENT:
                return RESTAPIHelper::response([], true, 'We have sent a new password to your email. Please also check Junk/Spam folder as well.' );
                break;
            default:
                return RESTAPIHelper::response('Unexpected error occurred.', false);
                break;
        }
    }

    public function resendVerificationEmail(Request $request) {
        $userRequested = User::users()->whereEmail($request->get('email', ''))->first();

        if ( !$userRequested )
            return RESTAPIHelper::response('Email not found in the system.', false, 'invalid_email');

        event(new JWTUserRegistration($userRequested, [
            'syncFirestore' => false,
        ]));

        return RESTAPIHelper::response([], true, 'We have sent a new password to your email. Please also check Junk/Spam folder as well.' );
    }

    public function viewMyProfile(Request $request)
    {
        $me = $this->getUserInstance();

        $result = $this->generateUserProfileResponse( $me );

        return RESTAPIHelper::response( $result );
    }

    public function updateMyProfile(UserUpdateRequest $request)
    {
        $me = $this->getUserInstance();

        // This will work with empty fields.
        // When user wants to set empty value on particular fields it would work that way.
        // Also, accepts when few items provided while updating
        $dataToUpdate = array_filter([
            'first_name'           =>  $request->get('first_name', false),
            'last_name'            =>  $request->get('last_name', false),
            'email'                =>  $request->get('email', false),
            'profile_picture'      =>  $request->get('profile_picture', false),
        ], function($a){return false !== $a;});

        if ( $request->has('phone') ) {
            try {
                $dataToUpdate['phone'] = phone($request->get('phone'), 'US')->formatE164();
            } catch (\Exception $e) {
                $dataToUpdate['phone'] = '';
            }
        }

        if ( $request->has('password') && $request->get('password', '') !== '' ) {

            // Validate old password first
            $oldPasswordValidation = Auth::validate([
                'email' => $me->email,
                'password' => $request->get('old_pwd'),
            ]);

            if ( !$oldPasswordValidation ) {
                return RESTAPIHelper::response('Old password is incorrect', false, 'auth_error');
            }

            $dataToUpdate['password'] = bcrypt( $request->get('password') );
        }

        if ( $request->hasFile('profile_picture') ) {

            if ( !in_array($request->file('profile_picture')->getClientOriginalExtension(), ['jpg','jpeg','png','bmp']) ) {
                return RESTAPIHelper::response('Invalid profile_picture given. Please use only image as your profile picture.', false, 'validation_error');
            }

            $imageName = $me->id . '-' . str_random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path = public_path( config('constants.front.dir.profilePicPath') );
            $request->file('profile_picture')->move($path, $imageName);

            if ( Image::open( $path . '/' . $imageName )->scaleResize(200, 200)->save( $path . '/' . $imageName ) ) {
                $dataToUpdate['profile_picture'] = $imageName;
                $oldImageToDelete = $me->profile_picture;
            }
        }

        /*if ( array_key_exists('full_name', $dataToUpdate) ) {
            list($dataToUpdate['first_name'], $dataToUpdate['last_name']) = str_split_name($dataToUpdate['full_name']);
        }*/

        if ( empty($dataToUpdate) ) {
            return RESTAPIHelper::response('Nothing to update', false);
        }

        $me->update( $dataToUpdate );

        // Delete old image to avoid garbage collection
        if ( isset($oldImageToDelete) ) {
            unlink($path . '/' . $oldImageToDelete);
        }

        // Add user device
        if ( !empty($request->get('device_token', '')) ) {
            $me->updateDevice( $this->extractToken(), $request->get('device_token', ''), $request->get('device_type', null) );
        }

        // Trigger some action upon changing password
        if ( array_key_exists('password', $dataToUpdate) ) {
            event(new UserPasswordChanged($me, $dataToUpdate));
        }

        // Fire user update event
        event(new JWTUserUpdate($me));

        $result = $this->generateUserProfileResponse( $me );

        return RESTAPIHelper::response( $result, true, 'Profile updated successfully.' );

    }

    public function viewProfile(Request $request, $userId)
    {
        $me   = $this->getUserInstance();
        $user = User::users()->active()->find($userId);

        if ( !$user || !$user->isApiUser() ) {
            return RESTAPIHelper::response('Something went wrong here.', false);
        }

        $payload = $this->generateUserProfileResponse( $user );

        return RESTAPIHelper::response( $payload );
    }

}
