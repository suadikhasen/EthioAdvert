<?php

namespace App\Http\Controllers\Admin\Packages;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Services\Common\PackageService;
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

    public function save(PackageRequest $request)
    {   
      if(!PackageService::validPackageTime($request->package_initial_posting_time,$request->package_final_posting_time)){
          return back()->with('error_notification','final time must be greater than by 2 hour')->withInput($request->all());
         }elseif(!PackageService::checkPackageUniqueness($request->package_level,$request->initial_posting_time,$request->package_final_posting_time,$request->package_number_of_days))
          return back()->with('error_notification','package already exist')->withInput($request->all());
          $package = PackageRepository::createPackage($request);
          return back()->with('success_notification','package added successfully');
    }

    public function all()
    {
       $packages = PackageRepository::allPackages();
       $tittle    = 'list of all packages';
       $table_header = 'list of all packages';
       return view('admin.packages.all_package',compact(['packages','tittle','table_header']));
    }
}
