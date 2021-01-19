<?php

namespace App\Http\Controllers\Admin\Levels;

use App\Http\Controllers\Controller;
use App\Http\Requests\updateAttributeRequest;
use App\Services\Common\LevelAttributeService;
use App\TelgramBot\Database\Admin\LevelAttributeRepository;
use App\Http\Requests\AttributeRequest;

class LevelAttributeController extends Controller
{
    public function allAttributes()
    {
       $attributes = LevelAttributeRepository::allAttributes();
       $page_tittle = 'list of level attributes';
       $total_percents = LevelAttributeRepository::sumOfPercents();
       $column_headers = ['Id','Attribute Name','Attribute Maximum Values','Attribute  Percentage Value'];
       $column_keys    = ['id','attributes_name','attribute_maximum_value','attribute_percentage_value'];
       return view('admin.levels.level_attributes',compact('attributes','page_tittle','column_headers','column_keys','total_percents')); 
    }

    public function addNewAttributePage()
    {
        $page_tittle = 'add new level attribute';
        return view('admin.levels.add_or_edit_attribute',compact(['page_tittle']));
         
    }

    public function saveNewAttribute(AttributeRequest $request)
    {
       LevelAttributeRepository::createAttribute($request);   
       return back()->with('succes_notification','attribute added successfully'); 
      }

    public function editAttributePage($id)
    {
      $attribute = LevelAttributeRepository::findLevelAttribute($id);
      $page_tittle = 'edit level attribute';
      return view('admin.levels.add_or_edit_attribute',compact(['attribute','page_tittle']));
    }

    public function editAttribute(updateAttributeRequest $request, $id)
    {   
        if(!LevelAttributeService::validNameOfAttribute($request->attribute_name,$id))
          return back()->with('error_notification','attribute name must be unique');
        elseif(!LevelAttributeService::vlidPercentageForUpdate($id,$request->attribute_percentage_value))
          return back()->with('error_notification','sum of percentage must not be greater than 100'); 
        LevelAttributeRepository::updateAttribute($request,$id);
        return back()->with('succes_notification','attribute updated  successfully'); 

    }

    public function deleteAttribute($id)
    {
        LevelAttributeRepository::deleteLevelAttribute($id);
        return back()->with('success_notification','attribute delete successfully');
    }
}
