<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralStatusHistory extends Model
{
    protected $fillable = ['status'];

    public function setUpdatedAt($value)
    {
        return $this;
    }

    public function getUpdatedAtColumn()
    {
        return null;
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
