<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $fillable = ['title', 'is_active'];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
