<?php

namespace App\Services\Common;

use App\TelgramBot\Database\Admin\LevelAttributeRepository;

class LevelAttributeService 
{
    public static function validNameOfAttribute($attribute_name,$id)
    {
       $attribute = LevelAttributeRepository::findLevelAttribute($id);
       if($attribute->attributes_name === $attribute_name)
         return true;
       elseif(LevelAttributeRepository::checkExistenceofAttributeName($attribute_name))
          return false;
       else
        return true;     
    }

    public static function vlidPercentageForUpdate($attribute_id,$current_percent)
    {
        $total_percent = LevelAttributeRepository::sumOfPercents();
        $attribute = LevelAttributeRepository::findLevelAttribute($attribute_id);
        $percentage_difference = $total_percent-$attribute->attribute_percentage_value;
        if(($percentage_difference+$current_percent) > 100)
            return false;
        return true;    
    }

}