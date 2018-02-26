<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'is_active'];

    protected $casts = [
        'is_24_7_phone' => 'boolean',
    ];

    public function getPhoneAttribute()
    {
        return strval($this->attributes['phone']);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
