<?php

namespace App\Http\Controllers\Admin\TransactionNumber;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\TransactionNumberRepository;
use App\TransactionNumbers;
use Illuminate\Http\Request;

class TransactionNumberController extends Controller
{
    public function listOfTransactionNumbers()
    {
        $transaction_numbers = TransactionNumberRepository::all();
        return view('admin.advertiser.transaction_number',compact('transaction_numbers'));
       
    }
}
