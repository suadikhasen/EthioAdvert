<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use App\TelgramBot\Database\Admin\AdminRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
     public function index()
     {   
         $admin = AdminRepository::findAdmin(Auth::guard('admin')->user()->id);
         return view('admin.profile.index',['admin' => $admin]);
     }

     public function  changePasswordPage()
     {
       return view('admin.profile.changepassword');
     }

     public function changePassword(ChangePasswordRequest $changePasswordRequest)
     {  
        $user = Auth::guard('admin')->user();
        $check = Hash::check($changePasswordRequest->old_password, $user->password); 
        if($check){
          AdminRepository::updatePassword($changePasswordRequest->current_password,$user->id);
          return back()->with('success_notification','password changed successfully');
        }
        return back()->with('error_notification','incorrect old password');
     }

     public function addAdminPage()
     {
        return view('admin.profile.add_admin');
     }

     public function addAdmin(AddAdminRequest $request)
     {
        AdminRepository::addNewAdmin($request);
        return back()->with('success_notification','admin added successfully');        
     }




}
