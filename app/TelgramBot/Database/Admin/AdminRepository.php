<?php

namespace App\TelgramBot\Database\Admin;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class AdminRepository 
{
    public static function findAdmin($id)
    {
      return Admin::find($id);
    }

    public static function updatePassword($current_password,$id)
    {
      Admin::find($id)->update([
        'password' => Hash::make($current_password)
      ]);
    }

    public static function addNewAdmin($request)
    {
      Admin::create([
          'email' => $request->email,
          'full_name' => $request->full_name,
          'password'  => $request->password,
      ]);
    }

    

    
}