<?php

namespace App\TelgramBot\Database\Admin;

use App\Package;

class  PackageRepository

{   
    public static function allPackages()
    {
        return  Package::with('level')->simplePaginate(10);
    }
}