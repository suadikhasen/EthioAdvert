<?php


namespace App\TelgramBot\Database;


use App\BankAccount;

class BankRepository
{
  public static function getBankInformation($id)
  {
    return BankAccount::findOrFail($id);
  }

  public static function getAllBank()
  {
      return BankAccount::all();
  }

    public static function getBankInformationByName(string $bank_name)
    {
        return BankAccount::where('bank_name',$bank_name)->first();
    }
}
