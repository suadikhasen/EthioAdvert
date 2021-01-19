<?php

namespace App\TelgramBot\Database\Admin;

use App\TransactionNumbers;

class TransactionNumberRepository
{
    public static function all()
    {
      return  TransactionNumbers::with('paymentMethod')->simplePaginate();
    }
}