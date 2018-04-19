<?php

namespace App\Http\Controllers\Backend;

use App\Classes\RijndaelEncryption;
use App\Models\City;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends BackendController
{
    protected $thisModule = [];

    public function getIndex()
    {
        $methodName = 'get' . ucfirst(user()->user_role_key) . 'Index';
        return app(__CLASS__)->$methodName();
    }

    public function getAdminIndex()
    {
        $allUsers = User::users()->get();

        $verifiedUsers = $allUsers->filter(function ($record) {
            return ($record->isVerified());
        });

        $stats                       = new \stdClass;
        $stats->total_users          = $allUsers->count();
        $stats->total_hospital       = User::whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES)->count();
        $stats->total_physician      = User::whereRoleId(User::ROLE_PHYSICIANS)->count();
        $stats->total_verified_users = $verifiedUsers->count();

        return backend_view('dashboard', compact('stats'));
    }

    public function getCities($stateID)
    {
        return City::listCities($stateID);
    }

    public function editProfile(Request $request)
    {
        $record = Auth::user();

        if ($request->getMethod() == 'GET')
        {
            return backend_view('settings.profile', compact('record'));
        }

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|max:255|unique:users,email,' . $record->id . ',id',
            'password'   => ($request->get('password') != '' ? 'min:8|case_diff|numbers|letters|symbols' : ''),
        ],
        [
            'password.regex' => 'Password must contain upper case, lower case and symbols'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $postData = $request->except('password');

        // Filter out remove images if selected and configured in access modifiers
        foreach (['profile_picture'] as $field)
        {
            if (isset($postData['remove_' . $field]) && $postData['remove_' . $field] == '1')
            {
                $postData[$field] = '';

                // Delete file as well
                $this->safelyRemoveFile(public_path($record->profile_picture_path));
            }
        }

        if ($request->hasFile('profile_picture'))
        {
            $imageName = $record->id . '-' . str_random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $path      = public_path(config('constants.front.dir.profilePicPath'));
            $request->file('profile_picture')->move($path, $imageName);
            $postData['profile_picture'] = $imageName;
        }

        if ($request->has('password') && $request->get('password', '') != '')
        {
            $postData['password'] = bcrypt($request->get('password'));
        }

        $record->update($postData);

        session()->flash('alert-success', 'Your profile has been updated successfully!');
        return redirect(route('backend.profile.setting'));
    }

    public function editSettings(Request $request)
    {
        $ratePerMile = Setting::where('config_key', 'setting.rate_per_mile')->first();
        $ratePerMile = ($ratePerMile) ? $ratePerMile->config_value : '';

        if ($request->getMethod() == 'GET') {
            return backend_view('settings.settings', compact('ratePerMile'));
        }

        Setting::updateSettingArray([
            ['config_key' => 'setting.rate_per_mile', 'config_value' => $request->get('rate_per_mile')],
        ]);

        session()->flash('alert-success', 'Settings have been updated successfully!');
        return redirect(route('backend.settings'));
    }

    public function getEmployeeIndex()
    {
        $allUsers = User::users()->whereHospitalId(user()->hospital_id)->get();

        $verifiedUsers = $allUsers->filter(function ($record) {
            return ($record->isVerified());
        });

        $stats                           = new \stdClass;
        $stats->total_hospital_physician = $allUsers->count();
        $stats->total_hospital_employee  = User::whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES)->whereHospitalId(user()->hospital_id)->count();
        $stats->total_verified_physician = $verifiedUsers->count();

        return backend_view('dashboard', compact('stats'));

    }
}
