<?php
namespace App\Http\Controllers\Api;

use App\Classes\Email;
use App\Classes\RijndaelEncryption;
use App\Events\Api\JWTUserLogin;
use App\Events\Api\JWTUserLogout;
use App\Events\Api\JWTUserRegistration;
use App\Events\Api\JWTUserUpdate;
use Gregwar\Image\Image;

//use App\Events\Api\NotificationsListed;
use App\Events\UserPasswordChanged;
use App\Helpers\RESTAPIHelper;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\City;
use App\Models\Hospital;
use App\Models\Profession;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Carbon\Carbon;
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
        $allInOneConfigs                  = [];
        $allInOneConfigs['professions']   = Profession::active()->pluck('title', 'id');
        $allInOneConfigs['hospitals']     = Hospital::active()->pluck('title', 'id');
        $allInOneConfigs['contact_email'] = Setting::extract('email.contact', '');
        $allInOneConfigs['about_us']      = Setting::extract('cms.about_us', '');
        $allInOneConfigs['reasons']       = [
            'App feedback',
            'Referral feedback',
            'Approval/Disapproval feedback',
            'Other',
        ];
        $allInOneConfigs['locations']     = [
            'Colorado Acute Long Term Hospital - Colorado Acute Long Term Hospital',
            'Complex Care Hospital at Ridgelake - Complex Care Hospital at Ridgelake',
            'Complex Care Hospital at Tenaya - Complex Care Hospital at Tenaya',
            'LifeCare Hospitals of Chester County - LifeCare Hospitals of Chester County',
            'LifeCare Hospitals of Dallas - LifeCare Hospitals of Dallas',
            'LifeCare Hospitals of Dayton - LifeCare Hospitals of Dayton',
            'LifeCare Hospitals of Fort Worth - LifeCare Hospitals of Fort Worth',
            'LifeCare Hospitals of Mechanicsburg - LifeCare Hospitals of Mechanicsburg',
            'LifeCare Hospitals of North Carolina - LifeCare Hospitals of North Carolina',
            'LifeCare Hospitals of Pittsburgh -  LifeCare Behavioral Hospital of Pittsburgh',
            'LifeCare Hospitals of Pittsburgh - Alle-Kiski Campus',
            'LifeCare Hospitals of Pittsburgh - Main Campus',
            'LifeCare Hospitals of Pittsburgh - Suburban Campus',
            'LifeCare Hospitals of Pittsburgh - Transitional Care Center at Suburban Campus',
            'LifeCare Hospitals of Plano - LifeCare Hospitals of Plano',
            'LifeCare Hospitals of San Antonio - LifeCare Hospitals of San Antonio',
            'LifeCare Hospitals of Shreveport - Main Campus',
            'LifeCare Hospitals of Shreveport - North Campus',
            'LifeCare Hospitals of Shreveport - Pierremont Campus',
            'LifeCare Hospitals of Wisconsin - LifeCare Hospitals of Wisconsin',
            'Tahoe Pacific Hospitals - Tahoe Pacific Hospitals – Meadows',
            'Tahoe Pacific Hospitals - Tahoe Pacific Hospitals – North',
        ];

        return RESTAPIHelper::response( $allInOneConfigs );
    }

    public function register(UserRegisterRequest $request)
    {
        $input             = $request->all();

        $input['password'] = bcrypt($input['password']);
        $input['role_id']  = User::ROLE_PHYSICIANS;

        if ( $request->has('phone') )
        {
            try
            {
                $input['phone'] = phone($request->get('phone'), 'US')->formatE164();
            }
            catch (\Exception $e)
            {
                $input['phone'] = '';
            }
        }

        if ( $request->hasFile('profile_picture') )
        {
            $imageName  = \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path       = public_path( config('constants.front.dir.profilePicPath') );
            $request->file('profile_picture')->move($path, $imageName);

            if ( Image::open( $path . '/' . $imageName )->scaleResize(200, 200)->save( $path . '/' . $imageName ) )
            {
                $input['profile_picture'] = $imageName;
            }
        }

        $user = User::create($input);
        $user = User::find($user->id); // Just because we need complete model attributes for event based activities

        // HIGH | TODO: Change is_active flag to `0` so that admin can approve this account.
        $user->email_verification = str_random(100);
        $user->is_active          = 0;
        $user->save();

        // Fire user registration event
        event(new JWTUserRegistration($user));

        if ( $user->email_verification != 1 )
        {
            return RESTAPIHelper::response(new \stdClass, true, 'Your account has been registered and email address requires verification. A verification code is sent to your email. Please also check Junk/Spam folder as well.');
        }
        else if( $user->is_active != 1 )
        {
            return RESTAPIHelper::response(new \stdClass, true, 'Your account has been registered and requires admin approval. We will notify you once admin approves your account.');
        }
        else
        {
            return $this->login($request);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'        => 'required',
            'password'     => 'required',
            'device_type'  => 'in:ios,android',
            'device_token' => 'string',
        ]);

        if ($validator->fails())
        {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        $input = $request->only(['email', 'password']);

        // Allow only following role
        $input['role_id'] = User::ROLE_PHYSICIANS;

        if (!$token = JWTAuth::attempt($input))
        {
            return RESTAPIHelper::response('Invalid credentials, please try-again.', false);
        }

        $userData = JWTAuth::toUser($token);

        /* Do your additional/manual validation here like email verification or enable/disable */
        try
        {
            $userData->validateUserActiveCriteria();
        }
        catch (\App\Exceptions\UserNotAllowedToLogin $e)
        {
            return RESTAPIHelper::response($e->getMessage(), false, $e->getResolvedErrorCode());
        }

        $userData->{'2fa'} = mt_rand(111111, 999999);
        $userData->save();

        $userData->notify( new \App\Notifications\Api\UserTwoFactorVerification($userData) );

        return RESTAPIHelper::response(new \stdClass, true, 'Please enter 6 digit code which we\'ve sent in to your email.');

    }

    public function logout(Request $request)
    {
        try
        {
            $me = $this->getUserInstance();

            if ( $me )
            {
                $me->removeDevice( $this->extractToken() );

                // Fire user logout event
                event(new JWTUserLogout($me, [
                    'sendLogoutPush' => false,
                ]));
            }

            JWTAuth::invalidate( $this->extractToken() );

        }
        catch (Exception $e)
        {

        }

        return RESTAPIHelper::emptyResponse();
    }

    public function resetPassword(Request $request)
    {
        $response = \Password::broker()->sendResetLink($request->only('email'));

        switch ($response)
        {
            case \Password::INVALID_USER:
                return RESTAPIHelper::response('Email not found in the system.', false, 'invalid_email');
                break;
            case \Password::RESET_LINK_SENT:
                return RESTAPIHelper::response(new \stdClass, true, 'We have sent a new password to your email. Please also check Junk/Spam folder as well.' );
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

        return RESTAPIHelper::response(new \stdClass, true, 'We have sent a new password to your email. Please also check Junk/Spam folder as well.' );
    }

    public function verify2FaCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'code'  => 'required|string|size:6',
        ]);

        if ($validator->fails())
        {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        $userData = User::users()
            ->where('2fa', $request->get('code', ''))
            ->whereEmail($request->get('email', ''))
            ->first();

        if ( !$userData )
            return RESTAPIHelper::response('Account does not exist in system.', false, 'invalid_account');

        $token = JWTAuth::fromUser($userData);

        $userData->removeDevice();
        // Add user device
        $userData->addDevice( $request->get('device_token', ''), $request->get('device_type', null), $token );

        // Generate user response
        $result = $this->generateUserProfileResponse( $userData, $token );

        event(new JWTUserLogin($userData));

        return RESTAPIHelper::response( $result );
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
            'first_name'            =>  $request->get('first_name', false),
            'last_name'             =>  $request->get('last_name', false),
            'email'                 =>  $request->get('email', false),
            'profile_picture'       =>  $request->get('profile_picture', false),
            'profession_id'         =>  $request->get('profession_id', false),
            'hospital_id'           =>  $request->get('hospital_id', false),
        ], function($a){return false !== $a;});

        if ( $request->has('phone') )
        {
            try
            {
                $dataToUpdate['phone'] = phone($request->get('phone'), 'US')->formatE164();
            }
            catch (\Exception $e)
            {
                $dataToUpdate['phone'] = '';
            }
        }

        if ( $request->has('password') && $request->get('password', '') !== '' )
        {
            // Validate old password first
            $oldPasswordValidation = Auth::validate(['email'=> $me->email,'password' => $request->get('old_pwd')]);

            if ( !$oldPasswordValidation )
            {
                return RESTAPIHelper::response('Old password is incorrect', false, 'auth_error');
            }

            $dataToUpdate['password'] = bcrypt( $request->get('password') );
        }

        if ( $request->hasFile('profile_picture') )
        {

            if ( !in_array($request->file('profile_picture')->getClientOriginalExtension(), ['jpg','jpeg','png','bmp']) )
            {
                return RESTAPIHelper::response('Invalid profile_picture given. Please use only image as your profile picture.', false, 'validation_error');
            }

            $imageName = $me->id . '-' . str_random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $path = public_path( config('constants.front.dir.profilePicPath') );

            $request->file('profile_picture')->move($path, $imageName);

            if ( Image::open( $path . '/' . $imageName )->scaleResize(200, 200)->save( $path . '/' . $imageName ) )
            {
                $dataToUpdate['profile_picture'] = $imageName;

                if(is_file(public_path( config('constants.front.dir.profilePicPath').$me->profile_picture)))
                {
                    $oldImageToDelete                = $me->profile_picture;
                }
            }
        }

        if ( empty($dataToUpdate) )
        {
            return RESTAPIHelper::response('Nothing to update', false);
        }

//        foreach (collect(User::getEncryptionFields()) as $field)
//        {
//            if(array_key_exists($field, $dataToUpdate))
//            {
//                $dataToUpdate[$field] = RijndaelEncryption::encrypt($dataToUpdate[$field]);
//            }
//        }

        $me->update( $dataToUpdate );

        // Delete old image to avoid garbage collection
        if ( isset($oldImageToDelete) )
        {
            unlink($path . '/' . $oldImageToDelete);
        }

        // Add user device
        if ( !empty($request->get('device_token', '')) )
        {
            $me->updateDevice( $this->extractToken(), $request->get('device_token', ''), $request->get('device_type', null) );
        }

        // Trigger some action upon changing password
        if ( array_key_exists('password', $dataToUpdate) )
        {
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

    public function searchHospitalsByZipCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zipcode' => 'required_without:city|min:5|max:7',
            'city'    => 'required_without:zipcode',
        ]);

        if ($validator->fails()) {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        $perPage = $request->get('limit', constants('api.config.defaultPaginationLimit'));

        if ( $request->has('zipcode') )
        {
            $zipCode = preg_replace('%[^a-zA-Z0-9]%', '', $request->get('zipcode')); // replace everything except alpha-numeric
            $hospitals = Hospital::active()->where('zip_code', '=', $zipCode)->paginate($perPage);
        }
        else
        {
            $hospitals = Hospital::active()->where('location', 'like', '%'.$request->get('city').'%')->paginate($perPage);
        }

        return RESTAPIHelper::response( $hospitals->pluckMultiple([
            'id',
            'title',
            'address',
            'location',
            'zip_code',
            'latitude',
            'longitude',
            'timing_open',
            'timing_close',
            'phone',
            'is_24_7_phone',
        ], [
            'id' => 'hospital_id'
        ]) );
    }

    public function getHospitalDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hospital_id' => 'required',
        ]);

        if ($validator->fails()) {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        $hospital = Hospital::active()->find($request->get('hospital_id'));

        if (!$hospital) {
            return RESTAPIHelper::response('Hospital does not exist.', false, 'validation_error');
        }

        return RESTAPIHelper::response( collect($hospital)->only([
            'id',
            'title',
            'description',
            'address',
            'location',
            'zip_code',
            'latitude',
            'longitude',
            'timing_open',
            'timing_close',
            'phone',
            'is_24_7_phone',
        ]) );
    }

    public function submitReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'  => 'required',
            'last_name'   => 'required',
            'age'         => 'required',
            'phone'       => 'required',
            'diagnosis'   => 'required',
            'hospital_id' => 'required|exists:hospitals,id|min:1',
        ]);

        if ($validator->fails())
        {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        $hospital = Hospital::active()->find($request->get('hospital_id'));

        if (!$hospital)
        {
            return RESTAPIHelper::response('Hospital does not exist.', false, 'validation_error');
        }

        $me = $this->getUserInstance();

        $referral = new Referral($request->all());

        $referral->doctor()->associate($me);
        $referral->hospital()->associate($hospital);
        $referral->save();

        return RESTAPIHelper::response(new \stdClass, true, 'You have successfully referral a patient.');
    }

    public function getReferrals(Request $request)
    {
        $perPage = $request->get('limit', constants('api.config.defaultPaginationLimit'));

        $records = $this->getUserInstance()
            ->referrals()
            ->where('created_at', ">=", Carbon::now()->subDays(10)->startOfDay())
            ->orderBy('updated_at', 'DESC')
            ->paginate($perPage);

        return RESTAPIHelper::response( $records->pluckMultiple([
            'first_name',
            'last_name',
            'age',
            'phone',
            'diagnosis',
            'referral_reason',
            'status',
            'status_text_api',
            'hospital_title',
        ]) );
    }

    public function saveContactUs(Request $request)
    {
//        $requestData              = [];
//        $requestData['email']     = RijndaelEncryption::decrypt($request->get('email', ''));
//        $requestData['full_name'] = RijndaelEncryption::decrypt($request->get('full_name', ''));
//        $requestData['phone']     = RijndaelEncryption::decrypt($request->get('phone', ''));
//
//        $request->merge($requestData);

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
            'location'  => 'required',
            'reason'    => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return RESTAPIHelper::response(array_flatten($validator->messages()->toArray()), false, 'validation_error');
        }

        $body = "Hey Admin,

                Someone has an enquiry about LifeCare, please find the details:
                
                Name: %s
                Email: %s
                Phone: %s
                Reason: %s
                Location: %s
                Comments: %s
                ";

        $full_name = $request->get('full_name', '');
        $email     = $request->get('email', '');
        $phone     = $request->get('phone', '');

        $contact_email = Setting::extract('email.contact');

        $body = sprintf($body, $full_name, $email, $phone, $request->get('reason'), $request->get('location'), $request->get('content'));

        Email::contactUsEmailShoot($contact_email, 'Contact Us Enquiry', $body, $email);

        return RESTAPIHelper::response(new \stdClass, true, 'Form submitted successfully.');
    }

    public function criteria(Request $request)
    {
        return RESTAPIHelper::response(Setting::extract('cms.criteria'), true);
    }

}
