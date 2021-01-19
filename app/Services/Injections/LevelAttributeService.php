<?php

namespace App\Services\Injections;
use Illuminate\Support\Facades\Route;
class LevelAttributeService
{
    public  function checkRouteIsEditAttribute()
    {
        if(Route::currentRouteName() === 'admin.levels.edit_level_of_attributes')
           return true;
        return false;   
    }
}