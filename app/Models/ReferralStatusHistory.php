<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralStatusHistory extends Model
{
    protected $fillable = ['status', 'reason'];

    public function setUpdatedAt($value)
    {
        return $this;
    }

    public function getUpdatedAtColumn()
    {
        return null;
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
    /*
     * @Relationships
     */
    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
