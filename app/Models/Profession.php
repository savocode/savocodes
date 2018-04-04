<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $fillable = ['title', 'is_active'];


    public function getActiveTextAttribute()
    {
        return $this->attributes['is_active'] == '1' ?
            '<span class="label label-success">Active</span>' :
            '<span class="label label-danger">Inactive</span>';
    }
    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
