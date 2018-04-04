<?php

namespace App\Http\Controllers\Backend;

use App\Notifications\Backend\UserActivationEmail;
use App\Notifications\Backend\EmployeeDeleteEmail;

use App\Models\City;
use App\Models\Country;
use App\Models\Profession;
use App\Models\School;
use App\Models\State;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\UserVerification;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use View;
use Yajra\Datatables\Datatables;

class UserController extends BackendController
{
    private $thisModule = [
        // @var: Module properties
        'longModuleName'  => 'Users',
        'shortModuleName' => 'Users',
        'viewDir'         => 'users',
        'controller'      => 'users',

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

    public function index()
    {
        $states         = State::listStates(Country::DEFAULT_COUNTRY_ID)->toArray();
        $firstState     = key($states);
        $states         = ['' => 'Select State'] + $states;
        $cities         = ['' => 'Select City'] + City::listCities($firstState)->toArray();
        $professions    = ['' => 'Select Profession'] + Profession::all()->pluck('title', 'id')->toArray();
        $genders        = ['' => 'Select Gender', 'Male' => 'Male', 'Female' => 'Female'];

        return backend_view($this->thisModule['viewDir'] . '.index', compact('states', 'cities', 'professions', 'genders'));//, compact('states', 'cities', 'genders', 'ageRanges', 'userTypes', 'schools'));
    }

    public function verificationListPending()
    {
        $records = User::has('verification')->with('verification')->get();

        $moduleProperties = $this->thisModule + [
            'longModuleName' => 'User Verification',
        ];

        return backend_view($this->thisModule['viewDir'] . '.verification_list', compact('records', 'moduleProperties'));
    }

    public function handleVerification(Request $request, User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $verifyRequest = UserVerification::find($request->get('id'));

        if (!$verifyRequest) {
            abort(401);
        }

        if ($request->get('action') == 'approve') {
            $record->verify();

            $verifyRequest->is_approved = 1;
            $verifyRequest->save();

            // Email::shoot( $record->email, 'Verification Request Processed', sprintf("Dear %s,\n\nYour verification request has been approved, now verify badge will display along with your profile.\n\nThanks", trim($record->full_name)) );
            $record->notify(new \App\Notifications\Backend\UserVerificationRequestAccepted($record));

            session()->flash('alert-success', 'Request has been verified successfully!');

        } else if ($request->get('action') == 'reject') {
            // $record->unverify();

            $verifyRequest->is_approved = -1;
            $verifyRequest->save();

            // Email::shoot( $record->email, 'Verification Request Processed', sprintf("Dear %s,\n\nYour verification request has been rejected, Please ensure that you filled all required details properly and try again.\n\nThanks", trim($record->full_name)) );
            $record->notify(new \App\Notifications\Backend\UserVerificationRequestRejected($record));

            session()->flash('alert-success', 'Request has been rejected successfully!');

        } else {
            session()->flash('alert-danger', 'Error while handle request, please try again!');
        }

        return redirect('backend/' . $this->thisModule['controller'] . '/verification');
    }

    public function data(Datatables $datatables, Request $request)
    {
        $state       = $request->get('state');
        $city        = $request->get('city');
        $gender      = $request->get('gender');
        $profession  = $request->get('profession');

        $query = User::users();

        if ($state) {
            $query->where('state', $state);
        }

        if ($city) {
            $query->where('city', $city);
        }

        if ($profession) {
            $query->where('profession_id', $profession);
        }

        if ($gender) {
            $query->whereHas('metas', function ($query) use ($gender) {
                $query->where('key', 'gender')->where('value', 'LIKE', $gender . '%');
            });
        }

        return $datatables->eloquent($query)
            ->orderColumn('created_at', 'created_at $1')

            ->editColumn('first_name', function ($user) {
                return $user->first_name_decrypted;
            })
            ->editColumn('last_name', function ($user) {
                return $user->last_name_decrypted;
            })
            ->editColumn('email', function ($user) {
                return $user->email_decrypted;
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
                return backend_view($this->thisModule['viewDir'] . '.action', compact('record') + [
                    'edit' => false,
                ]);
            })
            ->rawColumns(['first_name', 'last_name', 'active', 'profile_picture', 'action'])
            ->make(true);
    }

    public function detail(User $record)
    {
        $userMeta = $record->getMetaMulti(UserMeta::GROUPING_PROFILE);

        return backend_view($this->thisModule['viewDir'] . '.detail', compact('record', 'userMeta'));
    }

    public function destroy(User $record)
    {
        if ($record->isAdmin() || in_array($record->id, array_get($this->thisModule, 'undeleteable', []))) {
            abort(404);
        }

        if(valid_email($record->email_decrypted))
            $record->notify(new EmployeeDeleteEmail($record));
        $record->delete();

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been deleted successfully!');
        return redirect('backend/' . $this->thisModule['controller']);
    }

    public function block(User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $record->deactivate();

        $record->notify(new UserActivationEmail($record, 0, 1));
        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been blocked successfully!');
        return redirect('backend/' . $this->thisModule['controller']);
    }

    public function unblock(User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $record->activate();

        $record->notify(new UserActivationEmail($record, 1, 1));
        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been unblocked successfully!');
        return redirect('backend/' . $this->thisModule['controller']);
    }

    public function verify(User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $record->verify();

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been marked as verified!');
        return redirect('backend/' . $this->thisModule['controller']);
    }

    public function unverify(User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $record->unverify();

        session()->flash('alert-success', 'Verified mark has been removed');
        return redirect('backend/' . $this->thisModule['controller']);
    }
}
