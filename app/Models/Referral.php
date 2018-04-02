<?php

namespace App\Models;

use App\Events\SavingReferral;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    const DEFAULT_STATUS = 0;

    protected $fillable = ['first_name', 'last_name', 'age', 'phone', 'diagnosis'];

    protected $casts = ['status' => 'integer'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'saved' => SavingReferral::class,
    ];

    /**
     * @Scopes
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'Pending';
                break;
            case 0:
                return 'Accepted';
                break;
            case 0:
                return 'Rejected';
                break;
            default:
                return 'Unknown';
                break;
        }
    }

    public function getHospitalTitleAttribute()
    {
        return $this->hospital->title;
    }

    public function getReferralReasonAttribute()
    {
        //return $this->statusHistory()->whereReferralId($this->attributes['id'])->get()->pluck('reason');//->reason;
        return $this->attributes['status'] > 0 ? $this->statusHistory()->get()->pluck('reason') : [];
    }

    /*
     * @Relationships
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ReferralStatusHistory::class);
    }
}
