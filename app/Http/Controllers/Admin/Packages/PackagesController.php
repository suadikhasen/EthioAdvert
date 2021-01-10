<?php

namespace App\Http\Controllers\Admin\Packages;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\LevelRepository;
use App\TelgramBot\Database\Admin\PackageRepository;
use App\TelgramBot\Database\PackageRepositoryService;

class PackagesController extends Controller
{
    public function addNew()
    { 
      $levels = LevelRepository::allLevel();  
      return view('admin.packages.add_new_package',compact('levels'));
    }

    public function save()
    {
        
    }

    public function all()
    {
       $packages = PackageRepository::allPackages();
       $tittle    = 'list of all packages';
       $table_header = 'list of all packages';
       return view('admin.packages.all_package',compact(['packages','tittle','table_header']));
    }
}
