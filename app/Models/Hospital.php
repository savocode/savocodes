<?php

namespace App\Models;

use Carbon\Carbon;
use Notification;
use App\Notifications\Backend\HospitalActivationEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'is_active',
        'description',
        'address',
        'location',
        'zip_code',
        'latitude',
        'longitude',
        'timing_open',
        'timing_close',
        'phone',
        'is_24_7_phone'
    ];

    protected $casts = [
        'is_24_7_phone' => 'boolean',
    ];

    public function getTimeFormatted($value)
    {
        return Carbon::parse($value)->format(constants('back.theme.modules.time_format'));
    }

    public function getPhoneAttribute()
    {
        return strval($this->attributes['phone']);
    }

    public function getIsPhoneAttribute()
    {
        return $this->attributes['is_24_7_phone'] == false?
            '<span class="label label-danger">NO</span>' :
            '<span class="label label-success">Yes</span>';
    }

    public function getActiveTextFormattedAttribute()
    {
        return $this->attributes['is_active'] == '1'?
            '<span class="label label-success">Active</span>' :
            '<span class="label label-danger">In-Active</span>';
    }

    public function getIsUpdatedAttribute()
    {
        if($this->attributes['created_at'] == $this->attributes['updated_at'] || $this->attributes['updated_at'] == NULL)
        {
            return '<span class="label label-danger">No</span>';
        }
        else
        {
            return '<span class="label label-success">Yes</span>' ;
        }
    }

    //Functions

    public function activate()
    {
        $this->is_active = 1;
        $this->save();

        $users = $this->users()->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES);//->get();
        $users->update(['is_active' => 1]);

        Notification::send($users->get(), new HospitalActivationEmail($this, 1));

       // event(new HospitalActivated($this));
    }

    public function deactivate()
    {
        $this->is_active = 0;
        $this->save();

        $users = $this->users()->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES);//->get();
        $users->update(['is_active' => 0]);

        Notification::send($users->get(), new HospitalActivationEmail($this, 0));

      //  event(new HospitalActivated($this));
    }


    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

//    public function scopeEmployee($query)
//    {
//        return $query->with(['user' => function($q){
//            $q->where('role_id',  User::ROLE_HOSPITAL_EMPLOYEES);
//        }]);
//    }

    public function users()
    {
        return $this->hasMany(User::class, 'hospital_id', 'id');
    }
}
