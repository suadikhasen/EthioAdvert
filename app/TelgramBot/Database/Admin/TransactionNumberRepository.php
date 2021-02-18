<?php

namespace App\TelgramBot\Database\Admin;

use App\TransactionNumbers;

class TransactionNumberRepository
{
    public static function all()
    {
      return  TransactionNumbers::with('paymentMethod')->simplePaginate();
    }

    public static function createTransaction($transaction)
    {
       TransactionNumbers::create([

          'ref_number'          => $transaction->transaction_number,
          'payment_method_code' => $transaction->payment_method,
          'amount'              => $transaction->amount,
       ]);
    }

    public static function isTransactionExist($transaction_number)
    {
       return TransactionNumbers::where('ref_number',$transaction_number)->exists();
    }

    public static function findTransaction($transaction_id)
    {
      return TransactionNumbers::find($transaction_id);
    }

    public static function updateTransaction($transaction_id,$transaction)
    {
       TransactionNumbers::find($transaction_id)->update([
         'ref_number'  => $transaction->transaction_number,
         'payment_method_code' => $transaction->payment_method
       ]);
    }

    public static function isTransactionNumberUsed($transaction_id)
    {
       return self::findTransaction($transaction_id)->used_status;
    }

    public static function deleteTransaction($transaction_id)
    {
       self::findTransaction($transaction_id)->delete();
    }
}