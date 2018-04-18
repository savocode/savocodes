<?php

namespace App\Http\Controllers\Backend;

use App\Classes\RijndaelEncryption;

use App\Events\ReferralStatusChange;

use App\Models\City;
use App\Models\Country;
use App\Models\Profession;
use App\Models\State;
use App\Models\User;
use App\Models\UserMeta;;
use App\Models\Referral;
use App\Models\ReferralStatusHistory;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class EmployeeController extends BackendController
{
    private $thisModule = [
        // @var: Module properties
        'longModuleName'        => 'Employees',
        'shortModuleName'       => 'Employees',
        'viewPhysicianDir'      => 'employees.physicians',
        'viewReferralDir'       => 'employees.referrals',
        'controllerPhysician'   => 'physicians',
        'controllerReferral'    => 'referrals',

        // @var: Module's field type
        'field.images'    => ['profile_picture'],

        // @array: Undeletable record id(s)
        'undeleteable'    => [1, 2],
        'uneditable'      => [1],
    ];

    public function __construct()
    {
        $this->thisModule['undeleteable'] = array_values(array_unique(array_merge([User::ADMIN_USER_ID], $this->thisModule['undeleteable'])));

        View::share([
            'moduleProperties' => $this->thisModule,
        ]);
    }

    //Physician Data

    public function physiciansIndex()
    {
        $states         = State::listStates(Country::DEFAULT_COUNTRY_ID)->toArray();
        $firstState     = key($states);
        $states         = ['' => 'Select State'] + $states;
        $cities         = ['' => 'Select City'] + City::listCities($firstState)->toArray();
        $professions    = ['' => 'Select Profession'] + Profession::all()->pluck('title', 'id')->toArray();
        $status         = ['' => 'Select Status', 0 => 'In Active', 1 => 'Active'];
        $hospital       = user()->hospital()->first();

        return backend_view($this->thisModule['viewPhysicianDir'] . '.index', compact('hospital', 'states', 'cities', 'professions', 'status'));
    }

    public function physiciansData(Datatables $datatables, Request $request)
    {
        $state       = $request->get('state');
        $city        = $request->get('city');
        $status      = $request->get('status');
        $profession  = $request->get('profession');

        $query = User::users()->whereHospitalId(user()->hospital_id);

        if ($state) {
            $query->where('state', $state);
        }

        if ($city) {
            $query->where('city', $city);
        }

        if ($profession) {
            $query->where('profession_id', $profession);
        }

        if ($status == '0' || !empty($status)) {
            $query->where('is_active', '=', intval($status));
        }

        return $datatables->eloquent($query)
//            ->filter(function ($query) {
//                if (request()->has('search.value')) {
//                    $query->search(request('search.value'));
//                }
//            }, false)
            ->order(function ($query) {
                $query->latest();
            })
            ->editColumn('first_name', function ($user) {
                return $user->first_name;
            })
            ->editColumn('last_name', function ($user) {
                return $user->last_name;
            })
            ->editColumn('email', function ($user) {
                return $user->email;
            })
            ->editColumn('active', function ($user) {
                return $user->status_text_formatted;
            })
            ->editColumn('profession_id', function ($user) {
                return ucfirst($user->profession_title);
            })
            ->editColumn('profile_picture', function ($user) {
                return '<a href="' . $user->profile_picture_auto . '" class="cboxImages">' . \Html::image($user->profile_picture_path, null, ['class' => 'img-responsive', 'style' => 'max-width: 50px;max-height: 50px']) . '</a>';
            })
            ->editColumn('created_at', function ($record) {
                return $record->created_at->format(constants('back.theme.modules.datetime_format'));
            })
            ->addColumn('action', function ($record) {
                return backend_view($this->thisModule['viewPhysicianDir'] . '.action', compact('record'));
            })
            ->rawColumns(['first_name', 'last_name', 'active', 'profile_picture', 'action'])
            ->make(true);
    }

    public function physiciansDetail(User $record)
    {
        $userMeta = $record->getMetaMulti(UserMeta::GROUPING_PROFILE);

        return backend_view($this->thisModule['viewPhysicianDir'] . '.detail', compact('record', 'userMeta'));
    }

    //Referral

    public function referralsIndex()
    {
        $hospital_id    = user()->hospital_id;
        $referral       = Referral::whereHospitalId($hospital_id);

        $diagnosis      = ['' => 'Select Diagnosis'] + $referral->groupBy('diagnosis')->get()->pluck('diagnosis_decrypted', 'id')->toArray();
        $age            = ['' => 'Select Age'] + $referral->groupBy('age')->get()->pluck('age_decrypted', 'id')->toArray();
        $status         = ['' => 'Select Status', '0' => 'Pending', '1' => 'Accepted', '2' => 'Rejected'] ;
        $hospital       = user()->hospital()->first();

        return backend_view($this->thisModule['viewReferralDir'] . '.index', compact('hospital', 'diagnosis', 'age', 'status'));
    }

    public function referralsData(Datatables $datatables, Request $request)
    {
        $diagnosis  = $request->get('diagnosis');
        $age        = $request->get('age');
        $status     = $request->get('status', '');

        $query = Referral::whereHospitalId(user()->hospital_id)->whereHas('doctor');//->with('doctor');

        if ($diagnosis && $diagnosis != 'Select Diagnosis') {
            $diagnosis = RijndaelEncryption::encrypt($diagnosis);
            $query->where('diagnosis', $diagnosis);
        }

        if ($age && $age != 'Select Age') {
            $age = RijndaelEncryption::encrypt($age);
            $query->where('age', $age);
        }

        if ($status == '0' || !empty($status)) {
            $query->where('status', intval($status));
        }

        return $datatables->eloquent($query)
            ->order(function ($query) {
                $query->latest();
            })
            ->editColumn('referred_by', function ($referral) {
                return $referral->doctor->full_name;
            })
            ->editColumn('first_name', function ($referral) {
                return $referral->first_name_decrypted;
            })
            ->editColumn('last_name', function ($referral) {
                return $referral->last_name_decrypted;
            })
            ->editColumn('age', function ($referral) {
                return $referral->age_decrypted;
            })
            ->editColumn('diagnosis', function ($referral) {
                return $referral->diagnosis_decrypted;
            })
            ->editColumn('status', function ($user) {
                return $user->status_text;
            })

            ->editColumn('created_at', function ($record) {
                return $record->created_at->format(constants('back.theme.modules.datetime_format'));
            })
            ->addColumn('action', function ($record) {
                return backend_view($this->thisModule['viewReferralDir'] . '.action', compact('record'));
            })
            ->rawColumns(['referred_by', 'first_name', 'last_name', 'status', 'action'])
            ->make(true);
    }

    public function referralsDetail(Referral $record)
    {
        $history = ReferralStatusHistory::whereReferralId($record->id)->latest()->get();

        return backend_view($this->thisModule['viewReferralDir'] . '.detail', compact('record', 'history'));
    }

    public function referralsAccept(Referral $record, Request $request)
    {
        $record->status = 1;
        $record->save();

        $user = $record->doctor()->first();

        event(new ReferralStatusChange($record, $user, 1, $request->reason));

        session()->flash('alert-success', 'Referral has been accepted successfully');
        return redirect()->back();

    }

    public function referralsReject(Referral $record, Request $request)
    {
        $record->status = 2;
        $record->save();

        $user = $record->doctor()->first();

        event(new ReferralStatusChange($record, $user, 0, $request->reason));

        session()->flash('alert-success', 'Referral has been rejected successfully');
        return redirect()->back();

    }

}
