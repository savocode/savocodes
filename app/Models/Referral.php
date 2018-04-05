<?php

namespace App\Models;

use App\Events\SavingReferral;
use App\Classes\RijndaelEncryption;
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
    public function getStatusTextApiAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'pending';
                break;
            case 1:
                return 'Accepted';
                break;
            case 2:
                return 'Rejected';
                break;
            default:
                return 'Unknown';
                break;
        }
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 0:
                return '<span class="label label-warning">Pending</span>';
                break;
            case 1:
                return '<span class="label label-success">Accepted</span>';
                break;
            case 2:
                return '<span class="label label-danger">Rejected</span>';
                break;
            default:
                return '<span class="label label-primary">Unknown</span>';
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

    public function getDiagnosisDecryptedAttribute()
    {
        return RijndaelEncryption::decrypt($this->attributes['diagnosis']);// . ' ' . RijndaelEncryption::decrypt($this->attributes['last_name']);
    }

    public function getAgeDecryptedAttribute()
    {
        return RijndaelEncryption::decrypt($this->attributes['age']);// . ' ' . RijndaelEncryption::decrypt($this->attributes['last_name']);
    }

    public function getFirstNameDecryptedAttribute()
    {
        return RijndaelEncryption::decrypt($this->attributes['first_name']);// . ' ' . RijndaelEncryption::decrypt($this->attributes['last_name']);
    }

    public function getLastNameDecryptedAttribute()
    {
        return RijndaelEncryption::decrypt($this->attributes['last_name']);// . ' ' . RijndaelEncryption::decrypt($this->attributes['last_name']);
    }

    public function getFullNameDecryptedAttribute()
    {
        return RijndaelEncryption::decrypt($this->attributes['first_name']) . ' ' . RijndaelEncryption::decrypt($this->attributes['last_name']);
    }

    public function getPhoneDecryptedAttribute()
    {
        return RijndaelEncryption::decrypt($this->attributes['phone']);// . ' ' . RijndaelEncryption::decrypt($this->attributes['last_name']);
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
