<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\SchoolRequest;
use App\Models\School;
use Illuminate\Http\Request;
use View;

class SchoolController extends BackendController
{
    private $thisModule = [
        // @var: Module properties
        'longModuleName'  => 'Schools',
        'shortModuleName' => 'School',
        'viewDir'         => 'modules.schools',
        'controller'      => 'schools',
    ];

    public function __construct()
    {
        View::share([
            'moduleProperties' => $this->thisModule,
        ]);
    }

    public function index()
    {
        $schools = School::get();
        return backend_view($this->thisModule['viewDir'] . '.index', compact('schools'));
    }

    public function edit(Request $request, School $record)
    {
        return backend_view($this->thisModule['viewDir'] . '.edit', compact('record'));
    }

    public function create(Request $request)
    {
        return backend_view($this->thisModule['viewDir'] . '.create');
    }

    public function save(SchoolRequest $request)
    {
        if (School::create($request->all())) {
            return redirect(route('schools.index'))->with('alert-success', 'School has been created!');
        }

        return redirect()->back()->with('alert-danger', 'School could not be created!');
    }

    public function update(SchoolRequest $request, School $record)
    {
        if ($record->update($request->all())) {
            return redirect(route('schools.index'))->with('alert-success', 'School has been updated!');
        }

        return redirect()->back()->with('alert-danger', 'School could not be updated!');
    }

    public function delete(School $record)
    {
        if ($record->delete()) {
            return redirect(route('schools.index'))->with('alert-success', 'School has been deleted!');
        }

        return redirect()->back()->with('alert-danger', 'School could not be deleted!');
    }
}
