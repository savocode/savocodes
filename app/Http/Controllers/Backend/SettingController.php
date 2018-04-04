<?php

namespace App\Http\Controllers\Backend;

use App\Models\Profession;
use App\Models\Setting;
use View;
use Illuminate\Http\Request;

class SettingController extends BackendController
{
    private $thisModule = [
        // @var: Module properties
        'longModuleName'            => 'Settings',
        'shortModuleName'           => 'Settings',
        'viewCriteriaDir'           => 'settings.criteria',
        'viewProfessionDir'         => 'settings.profession',
        'controllerCriteria'        => 'criteria',
        'controllerProfession'      => 'professions',

    ];

    public function __construct()
    {
        View::share([
            'moduleProperties' => $this->thisModule,
        ]);
    }

    public function criteriaIndex()
    {
        $criteria = Setting::extract('cms.criteria');

        return backend_view($this->thisModule['viewCriteriaDir'].'.index', compact('criteria'));
    }


    public function criteriaEdit(Request $request)
    {
        $inputs = $request->all();

        for($i=0; $i<count($inputs['title']); $i++)
        {
            $output[$i]['title'] = $inputs['title'][$i];

            for($j=0; $j<count($inputs['name'][$i]); $j++)
            {
                $output[$i]['sub_criteria'][$j]['name'] = $inputs['name'][$i][$j];
                $output[$i]['sub_criteria'][$j]['body'] = $inputs['body'][$i][$j];
            }
        }

        Setting::updateSetting('cms.criteria', json_encode($output, JSON_UNESCAPED_SLASHES), true);

        session()->flash('alert-success', 'Criteria has been updated successfully');
        return redirect()->back();

    }

    //Profession

    public function professionIndex()
    {
        $professions = Profession::all();
        return backend_view($this->thisModule['viewProfessionDir'].'.index', compact('professions'));
    }

    public function professionEdit(Profession $record, Request $request)
    {
        $record->title      = $request->profession;
        $record->is_active  = $request->is_active;

        if($record->isDirty())
        {
            $record->save();

            session()->flash('alert-success', 'Profession Updated Successfully');
            return redirect('backend/settings/profession');
        }

        session()->flash('alert-danger', 'Nothing to update');
        return redirect('backend/settings/profession');


    }

}
