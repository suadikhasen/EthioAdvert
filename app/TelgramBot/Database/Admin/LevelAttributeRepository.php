<?php

namespace App\TelgramBot\Database\Admin;

use App\LevelAttribute;

class LevelAttributeRepository

{
    public static function allAttributes()
    {
      return LevelAttribute::all();
    }

    public static function deleteLevelAttribute($id)
    {
        LevelAttribute::find($id)->delete();
    }

    public static function findLevelAttribute($id)
    {
       return LevelAttribute::find($id);
    }

    public static function updateAttribute($request,$id)
    {
      LevelAttribute::find($id)->update([
             'attributes_name' => $request->attribute_name,
             'attribute_maximum_value' => $request->attribute_maximum_value,
             'attribute_percentage_value' => $request->attribute_percentage_value

      ]);
    }

    public static function checkExistenceofAttributeName($attribute_name)
    {
       return LevelAttribute::where('attributes_name',$attribute_name)->exists();
    }

    public static function sumOfPercents()
    {
      return LevelAttribute::sum('attribute_percentage_value');
    }

    public static function createAttribute($request)
    {
      LevelAttribute::create([
        'attributes_name' => $request->attribute_name,
        'attribute_maximum_value' => $request->attribute_maximum_value,
        'attribute_percentage_value' => $request->attribute_percentage_value

     ]);
    }

    public static function findByName($query)
    {
      return LevelAttribute::where('attributes_name',$query)->first();
    }
}