<?php

namespace App\Models;

use App\Models\MetaData;

class UserMeta extends MetaData
{
   // const GROUPING_DRIVER  = 'driver';
    const GROUPING_PROFILE = 'profile';

    // Possible meta values for user object:
    // trips_canceled, gender, school_name, student_organization, graduation_year, postal_code, birth_date, rating, sync_friends, has_facebook_integrated, driving_license_no, vehicle_type, insurance_no, driver_documents, unread_notifications

    /**
     * @var array
     */
    protected $fillable = ['key', 'value', 'grouping'];

    protected $table = 'user_meta';
}
