<?php

namespace App\Models;


use App\Http\Traits\JWTUserTrait;
//use App\Http\Traits\Metable\Metable;

use App\Models\Setting;

use App\Notifications\Backend\ResetPassword as BackendResetPasswordNotification;
use App\Notifications\Frontend\ResetPassword as FrontendResetPasswordNotification;
use Exception;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Sofa\Eloquence\Eloquence;
use JWTAuth;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    const ROLE_ADMIN                = 1;


    const ADMIN_USER_ID             = 1;

    const BACKEND_ALLOW_ROLES = [
        self::ROLE_ADMIN,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_verification', 'email', 'password', 'role_id', 'first_name', 'last_name', 'phone', 'address', 'city', 'state', 'country', 'profile_picture',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active', 'boolean',
    ];


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if ( $this->isBackendUser() )
        {
            $this->notify(new BackendResetPasswordNotification($token));
        }
        else
        {
            $this->notify(new FrontendResetPasswordNotification($token));
        }
    }

    public static function extractUserId($user)
    {
        if ( $user instanceof User )
        {
            return $user->id;
        }

        return $user;
    }

    /*
     * @User related methods
     */


    public function validateUserActiveCriteria()
    {
        if ( $this->attributes['email_verification'] != '1' )
        {
            throw new \App\Exceptions\UserNotAllowedToLogin('Please verify your email first.', 'action_verify_email');
        }
        else if ( $this->attributes['sms_verification'] != '1' )
        {
            throw new \App\Exceptions\UserNotAllowedToLogin('Please verify your sms first.', 'action_verify_number');
        }
        else if ( $this->attributes['is_active'] != '1' )
        {
            throw new \App\Exceptions\UserNotAllowedToLogin('Your account is not in active state.', 'inactive_account');
        }
        else if ( !$this->isApiUser() )
        {
            throw new \App\Exceptions\UserNotAllowedToLogin('Invalid credentials, please try-again.', 'invalid_credentials');
        }

        return true;
    }

    public function isBackendUser()
    {
        return (defined('self::BACKEND_ALLOW_ROLES') && in_array($this->attributes['role_id'], self::BACKEND_ALLOW_ROLES));
    }

    public function isApiUser()
    {
        return (defined('self::API_ALLOW_ROLES') && in_array($this->attributes['role_id'], self::API_ALLOW_ROLES));
    }

    public function isVerified()
    {
        return (bool) (array_key_exists('email_verification', $this->attributes) && intval($this->attributes['email_verification']) === 1 && array_key_exists('sms_verification', $this->attributes) && intval($this->attributes['sms_verification']) === 1);
    }

    public function isAdmin()
    {
        return (bool) ($this->attributes['role_id'] == self::ROLE_ADMIN);
    }

    public function isSelf($user)
    {
        return (bool) ($this->attributes['id'] == self::extractUserId($user));
    }

    public function isEmailVerified()
    {
        return (bool) ($this->attributes['email_verification'] === 1);
    }

    public static function generateUniqueVerificationCode($length=6)
    {
        $code  = mt_rand( intval(str_repeat(1, $length)), intval(str_repeat(9, $length)) );
        $exist = self::whereEmailVerification($code)->count();

        return $exist ? self::generateUniqueVerificationCode() : $code;
    }


    public function activate()
    {
        $this->is_active = 1;
        $this->save();
    }

    public function deactivate()
    {
        $this->is_active = 0;
        $this->save();
    }

    public function addDevice($deviceToken, $deviceType, $authToken)
    {
        UserDevice::whereDeviceToken($deviceToken)->delete();

        return $this->devices()->create([
            'device_token' => $deviceToken,
            'device_type'  => $deviceType,
            'auth_token'   => $authToken,
        ]);
    }

    public function updateDevice($authToken, $deviceToken, $deviceType)
    {
        $record = $this->devices()->whereAuthToken($authToken)->limit(1)->first();

        if ( $record ) {
            $record->device_token = $deviceToken;
            $record->device_type = $deviceType;
            $record->save();
        }
    }

    public function removeDevice()
    {
        $records = $this->devices()->get();

        if ( $records )
        {
            try
            {
                foreach ($records as $record)
                {
                    JWTAuth::invalidate($record->auth_token);
                }
            }
            catch(\Exception $e)
            {

            }
            $this->devices()->delete();
        }
    }

    public function device()
    {
        return $this->devices()->orderBy('id', 'DESC')->first();
    }


    public function getUserRoleKeyAttribute()
    {
        switch($this->attributes['role_id'])
        {
            case self::ROLE_ADMIN:
                return 'admin';

            default:
                return '';
        }
    }

    public function getFullNameAttribute()
    {
        return ltrim($this->attributes['first_name'] . ' ' . $this->attributes['last_name']);
    }

    public function getProfilePicturePathAttribute()
    {
        return config('constants.front.dir.profilePicPath') . ($this->attributes['profile_picture'] ?: config('constants.front.default.profilePic'));
    }

    public function getProfilePictureAutoAttribute()
    {
        return asset( config('constants.front.dir.profilePicPath') . ($this->attributes['profile_picture'] ?: config('constants.front.default.profilePic')) );
    }

    public function getStatusTextFormattedAttribute()
    {
        return $this->attributes['is_active'] == '1' ?
            '<span class="label label-success">Active</span>' :
            '<span class="label label-danger">Inactive</span>';
    }


    public function getStateTitleAttribute()
    {
        try {
            return intval($this->attributes['state']) > 0 ? $this->userstate->name : '';
        } catch (Exception $e) {
            return '';
        }
    }

    public function getCityTitleAttribute()
    {
        try {
            return intval($this->attributes['city']) > 0 ? $this->usercity->name : '';
        } catch (Exception $e) {
            return '';
        }
    }


    /*
     * @Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '=', 1);
    }

    public function scopeUsers($query)
    {
        return $query->whereNotIn('role_id', self::BACKEND_ALLOW_ROLES);
    }

    public function scopeExcludeSelf($query)
    {
        return $query->where( $this->getKeyName(), '<>', self::extractUserId(JWTUserTrait::getUserInstance()) );
    }

    /*
     * @Relationships
     */

    public function devices()
    {
        return $this->hasMany('App\Models\UserDevice');
    }

    public function usercity()
    {
        return $this->belongsTo('App\Models\City', 'city', 'id');
    }

    public function userstate()
    {
        return $this->belongsTo('App\Models\State', 'state', 'id');
    }


}
