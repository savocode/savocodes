<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Email;
//use App\Notifications\Backend\EmployeeUpdate;
use Gregwar\Image\Image;
use App\Classes\RijndaelEncryption;

use App\Events\UserPasswordChanged;
use App\Events\EmployeeUpdate;
use App\Events\Backend\UserActivated;
use App\Events\Backend\UserDeactivated;

use App\Notifications\Backend\EmployeeDeleteEmail;

use App\Models\City;
use App\Models\Country;
use App\Models\Profession;
use App\Models\State;
use App\Models\User;
use App\Models\UserVerification;
use App\Models\Hospital;
use Carbon\Carbon;
use DB;
//use Dotenv\Validator;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use View;
use File;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class HospitalController extends BackendController
{
    private $thisModule = [
        // @var: Module properties
        'longModuleName'  => 'Hospitals',
        'shortModuleName' => 'Hospitals',
        'viewDir'         => 'hospitals',
        'controller'      => 'hospitals',

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
        $type = ['' => 'Select type', 'hospital' => 'Hospitals', 'health_care' => 'Health Care Center'];
        return backend_view($this->thisModule['viewDir'] . '.index', compact('type'));
    }

    public function data(Datatables $datatables, Request $request)
    {
        $type  = $request->get('type');
        $query = Hospital::query();

        if($type)
        {
            $query->where('type', $type);
        }

        return $datatables->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('search.value')) {
                    $query->where('title', 'like', '%'.request('search.value').'%');
                }
            }, false)
            ->orderColumn('created_at', 'created_at $1')

            ->editColumn('timing_open', function ($record) {
                return $record->getTimeFormatted($record->timing_open);
            })
            ->editColumn('timing_close', function ($record) {
                return $record->getTimeFormatted($record->timing_close);
            })
            ->editColumn('type', function ($record) {
                return $record->type_text;
            })
            ->editColumn('is_24_7_phone', function ($record) {
                return $record->is_phone;
            })
            ->editColumn('active', function ($record) {
                return $record->active_text_formatted;
            })
            ->editColumn('created_at', function ($record) {
                return $record->created_at->format(constants('back.theme.modules.datetime_format'));
            })
            ->addColumn('action', function ($record) {
                return backend_view($this->thisModule['viewDir'] . '.action', compact('record'));
            })
            ->rawColumns(['is_24_7_phone', 'active', 'action'])
            ->make(true);
    }

    public function detail(Hospital $record)
    {
        $user   = $record->users()->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES);

        return backend_view($this->thisModule['viewDir'] . '.detail', compact('record', 'user'));
    }

    public function destroy(Hospital $record)
    {
        $record->deleteHospital();

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been deleted successfully!');

        return redirect('backend/' . $this->thisModule['controller']);
    }

    public function block(Hospital $record)
    {
        $record->deactivate();

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been blocked successfully!');
        return redirect('backend/' . $this->thisModule['controller'] . '/index');
    }

    public function unblock(Hospital $record)
    {
        $record->activate();

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' has been unblocked successfully!');
        return redirect('backend/' . $this->thisModule['controller'] . '/index');
    }

    public function showCreateForm()
    {
        return backend_view($this->thisModule['viewDir'].'.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|unique:hospitals|max:500',
            'phone'         => 'string|phone:US,BE',
            'address'       => 'string|nullable',
            'location'      => 'string|nullable',
            'zip_code'      => 'required|string',
            'is_24_7_phone' => 'required|in:1,0',
            'timing_open'   => 'required|string|date_format:H:i',
            'timing_close'  => 'required|string|date_format:H:i|after:timing_open',
            'description'   => 'required|string|min:50',
            'type'          => 'required|in:hospital,health_care',
        ],
        [
            'title.unique'                  => 'This Hospital is already exist',
            'timing_close.after'            => 'The timing close must be a time after timing open',
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $input             = $request->all();
        $input['zip_code'] = preg_replace('%[^a-zA-Z0-9]%', '', $request->get('zip_code'));

        if ( $request->has('phone') )
        {
            try
            {
                $input['phone'] = phone($request->get('phone'), 'US')->formatE164();
            }
            catch (\Exception $e)
            {
                $input['phone'] = '';
            }
        }

        foreach ($input as $key => $value)
        {
            $input[$key] = strval($value);
        }

        $input['is_active'] = 1;

        $hospital = Hospital::create($input);

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' created successfully');

        return redirect('backend/'.$this->thisModule['controller'].'/detail/'. $hospital->id);


    }

    public function showEditForm(Hospital $record)
    {
        if(!$record)
        {
            session()->flash('alert-danger', str_singular($this->thisModule['shortModuleName']) . ' does not exist');
            return redirect()->back();
        }

        return backend_view($this->thisModule['viewDir'].'.edit', compact('record'));
    }

    public function update(Request $request, Hospital $record)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|unique:hospitals,title,'.$record->id.',id|max:500',
            'phone'         => 'string||phone:US,BE',
            'address'       => 'string|nullable',
            'location'      => 'string|nullable',
            'zip_code'      => 'required|string|min:5|max:7',
            'is_24_7_phone' => 'required|in:1,0',
            'timing_open'   => 'required|string|date_format:H:i',
            'timing_close'  => 'required|string|date_format:H:i|after:timing_open',
            'description'   => 'required|string|min:50',
            'type'          => 'required|in:hospital,health_care',
        ],
        [
            'title.unique'                  => 'This Hospital is already exist',
            'timing_close.after'            => 'The timing close must be a time after timing open',
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $dataToUpdate['title']          = $request->get('title', false);
        $dataToUpdate['type']           = $request->get('type', false);
        $dataToUpdate['description']    = $request->get('description', false);
        $dataToUpdate['address']        = $request->get('address', false);
        $dataToUpdate['location']       = $request->get('location', false);
        $dataToUpdate['zip_code']       = preg_replace('%[^a-zA-Z0-9]%', '', $request->get('zip_code'));//$request->get('zip_code', false);
        $dataToUpdate['timing_open']    = $request->get('timing_open', false);
        $dataToUpdate['timing_close']   = $request->get('timing_close', false);
        $dataToUpdate['is_24_7_phone']  = $request->get('is_24_7_phone', false);

        if ( $request->has('phone') )
        {
            try
            {
                $dataToUpdate['phone'] = phone($request->get('phone'), 'US')->formatE164();
            }
            catch (\Exception $e)
            {
                $dataToUpdate['phone'] = '';
            }
        }


        $dataToUpdate = array_filter($dataToUpdate, function($a){return false !== $a;});

        foreach ($dataToUpdate as $key => $value)
        {
            $dataToUpdate[$key] = strval($value);
        }

        if ( empty($dataToUpdate) )
        {
            session()->flash('alert-danger', 'Nothing to update');
            return redirect('backend/'.$this->thisModule['controller'].'/detail/'. $record->id);
        }

        $record->update($dataToUpdate);

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' updated successfully');

        return redirect('backend/'.$this->thisModule['controller'].'/detail/'. $record->id);
    }

    //Hospital Employees
    public function employees(Hospital $record)
    {
        if(!$record)
        {
            session()->flash('alert-danger', str_singular($this->thisModule['shortModuleName']) . ' does not exist');
            return redirect()->back();
        }

        $states         = State::listStates(Country::DEFAULT_COUNTRY_ID)->toArray();
        $firstState     = key($states);
        $states         = ['' => 'Select State'] + $states;
        $cities         = ['' => 'Select City'] + City::listCities($firstState)->toArray();
        $genders        = ['' => 'Select Gender', 'Male' => 'Male', 'Female' => 'Female'];

        return backend_view($this->thisModule['viewDir'] . '.employees.index', compact('record', 'states', 'cities', 'genders'));
    }

    public function employeesData(Datatables $datatables, Request $request, Hospital $record)
    {
        $query       = $record->users()->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES);
        $hospital    = $record;

        $state       = $request->get('state');
        $city        = $request->get('city');
        $gender      = $request->get('gender');

        if ($state)
        {
            $query->where('state', $state);
        }

        if ($city)
        {
            $query->where('city', $city);
        }


        if ($gender)
        {
            $query->whereHas('metas', function ($query) use ($gender) {
                $query->where('key', 'gender')->where('value', 'LIKE', $gender . '%');
            });
        }

        return $datatables->eloquent($query)

            ->orderColumn('created_at', 'created_at $1')

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
            ->editColumn('profile_picture', function ($user) {
                return '<a href="' . $user->profile_picture_auto . '" class="cboxImages">' . \Html::image($user->profile_picture_path, null, ['class' => 'img-responsive', 'style' => 'max-width: 50px;max-height: 50px']) . '</a>';
            })
            ->editColumn('created_at', function ($record) {
                return $record->created_at->format(constants('back.theme.modules.datetime_format'));
            })
            ->addColumn('action', function ($user) use($record) {
                return backend_view($this->thisModule['viewDir'] . '.employees.action', compact('record', 'user'));
            })
            ->rawColumns(['first_name', 'last_name', 'active', 'profile_picture', 'action'])
            ->make(true);
    }

    public function showEmployeeCreateForm(Hospital $record)
    {
        if(!$record)
        {
            session()->flash('alert-danger', str_singular($this->thisModule['shortModuleName']) . ' does not exist');
            return redirect()->back();
        }

        $hospital       = $record;
        $states         = State::listStates(Country::DEFAULT_COUNTRY_ID)->toArray();
        $firstState     = key($states);
        $states         = ['' => 'Select State'] + $states;
        $cities         = ['' => 'Select City']  + City::listCities($firstState)->toArray();
        $genders        = ['' => 'Select Gender', 'Male' => 'Male', 'Female' => 'Female'];
;
        return backend_view($this->thisModule['viewDir'] . '.employees.create', compact('hospital', 'states', 'cities', 'genders'));
    }

    public function createEmployee(Request $request, Hospital $record)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|string|email|unique_encrypted:users,email',
            'phone'             => 'required|string|phone:US,BE',
            'address'           => 'string|nullable',
            'state'             => 'numeric|nullable',
            'city'              => 'numeric|nullable',
            'profile_picture'   => 'image|mimes:jpeg,bmp,png|max:2000'
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $password = str_random(10);
        $input    = array_filter($request->all());

        $input['role_id']               = User::ROLE_HOSPITAL_EMPLOYEES;
        $input['password']              = bcrypt($password);
        $input['email_verification']    = '1';
        $input['2fa']                   = 0;

        if($request->hasFile('profile_picture'))
        {
            $imageName  = \Illuminate\Support\Str::random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $path       = public_path( config('constants.front.dir.profilePicPath') );

            $request->file('profile_picture')->move($path, $imageName);

            if ( Image::open( $path . '/' . $imageName )->scaleResize(200, 200)->save( $path . '/' . $imageName ) )
            {
                $input['profile_picture'] = $imageName;
            }

        }

        if ( $request->has('phone') )
        {
            try
            {
                $input['phone'] = phone($request->get('phone'), 'US')->formatE164();
            }
            catch (\Exception $e)
            {
                $input['phone'] = '';
            }
        }

        $user = new User($input);
        $user->hospital()->associate($record);
        $user->save();

        $user->notify( new \App\Notifications\Backend\EmployeePassword($user, $password, $record) );

        session()->flash('alert-success', $this->thisModule['shortModuleName']." Employee has been created successfully!");
        return redirect('backend/'. $this->thisModule['controller'] .'/'. $record->id.'/employees');

    }

    public function showEmployeeEditForm(Hospital $hospital, User $employee)
    {
        if(!$employee)
        {
            session()->flash('alert-danger', 'Employee does not exist');
            return redirect()->back();
        }

        if(!$hospital)
        {
            session()->flash('alert-danger', $this->thisModule['longModuleName'].' does not exist');
            return redirect()->back();
        }

        $user = $hospital->users()->whereId($employee->id)->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES)->first();

        if(!$user)
        {
            session()->flash('alert-danger', 'This '.$this->thisModule['longModuleName'].' employee does not exist');
            return redirect('backend/'.$this->thisModule['controller'].'/'.$hospital->id.'/employees');//->back();
        }

        $states         = State::listStates(Country::DEFAULT_COUNTRY_ID)->toArray();
        $state_id       = isset($employee->state)?$employee->state:key($states);
        $states         = ['' => 'Select State'] + $states;
        $cities         = ['' => 'Select City'] + City::listCities($state_id)->toArray();
        $genders        = ['' => 'Select Gender', 'Male' => 'Male', 'Female' => 'Female'];

        return backend_view($this->thisModule['controller'] . '.employees.edit', compact('employee', 'hospital', 'states', 'cities', 'genders'));
    }

    public function editEmployee(Request $request, Hospital $hospital, User $employee)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|string|email|unique:users,email,'.$request->id.',id',
            'phone'             => 'required|string|phone:US,BE|unique:users,phone,'.$request->id.',id',
            'address'           => 'string|nullable',
            'state'             => 'numeric|nullable',
            'city'              => 'numeric|nullable',
            'profile_picture'   => 'image|mimes:jpeg,bmp,png|max:2000'
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = $hospital->users()->whereId($employee->id)->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES)->first();

        if(!$user)
        {
            session()->flash('alert-danger', 'This '.$this->thisModule['longModuleName'].' employee does not exist');
            return redirect('backend/'.$this->thisModule['controller'].'/'.$hospital->id.'/employees');//->back();
        }

        $dataToUpdate   = array_filter($request->all());

        if ( $request->hasFile('profile_picture') )
        {
            $imageName  = $user->id . '-' . str_random(12) . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $path       = public_path( config('constants.front.dir.profilePicPath') );

            $request->file('profile_picture')->move($path, $imageName);

            if ( Image::open( $path . '/' . $imageName )->scaleResize(200, 200)->save( $path . '/' . $imageName ) )
            {
                $dataToUpdate['profile_picture'] = $imageName;
                $oldImageToDelete                = $user->profile_picture;
            }
        }

        if($request->has('password'))
        {
            $dataToUpdate['password']   = bcrypt($request->password);
        }

        if ( $request->has('phone') )
        {
            try
            {
                $dataToUpdate['phone'] = phone($request->get('phone'), 'US')->formatE164();
            }
            catch (\Exception $e)
            {
                $dataToUpdate['phone'] = '';
            }
        }

        $user->update($dataToUpdate);


        if ( isset($oldImageToDelete) )
        {
            File::delete($path . '' . $oldImageToDelete);
        }

        if ( array_key_exists('password', $dataToUpdate) )
        {
            event(new UserPasswordChanged($user, $request->password));
        }

        event(new EmployeeUpdate($user, array_key_exists('password', $dataToUpdate)));

        session()->flash('alert-success', $this->thisModule['shortModuleName']." Employee has been updated successfully!");
        return redirect('backend/'. $this->thisModule['controller'] .'/'. $hospital->id.'/employees');
    }

    public function detailEmployee(Hospital $hospital, User $record)
    {
        $user = $hospital->users()->whereId($record->id)->whereRoleId(User::ROLE_HOSPITAL_EMPLOYEES)->first();

        if(!$user)
        {
            session()->flash('alert-danger', 'This '.$this->thisModule['longModuleName'].' employee does not exist');
            return redirect('backend/'.$this->thisModule['controller'].'/'.$hospital->id.'/employees');//->back();
        }

        return backend_view($this->thisModule['controller'] . '.employees.detail', compact('record', 'hospital'));
    }

    public function blockEmployee(User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $record->deactivate();

        event(new UserDeactivated($record, false));

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' Employee has been blocked successfully!');
        return redirect()->back();//redirect('backend/' . $this->thisModule['controller']);
    }

    public function unblockEmployee(User $record)
    {
        if ($record->isAdmin()) {
            abort(401);
        }

        $record->activate();

        event(new UserActivated($record, true));

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' Employee has been unblocked successfully!');
        return redirect()->back();//redirect('backend/' . $this->thisModule['controller']);
    }

    public function destroyEmployee(User $record)
    {
        $record->notify(new EmployeeDeleteEmail($record));
        $record->delete();

        session()->flash('alert-success', str_singular($this->thisModule['shortModuleName']) . ' Employee has been deleted successfully!');
        return redirect()->back();
    }
}
