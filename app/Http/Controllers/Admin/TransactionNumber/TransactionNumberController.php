<?php

namespace App\Http\Controllers\Admin\TransactionNumber;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditTransactionRequest;
use App\Http\Requests\TransactionNumberRequest;
use App\TelgramBot\Database\Admin\ListOfPaymentMethodRepository;
use App\TelgramBot\Database\Admin\TransactionNumberRepository;


class TransactionNumberController extends Controller
{
    public function listOfTransactionNumbers()
    {
        $transaction_numbers = TransactionNumberRepository::all();
        return view('admin.advertiser.transaction_number',compact('transaction_numbers'));
       
    }

    public function addNewTransactionNumberPage()
    {  
       $payment_method =  ListOfPaymentMethodRepository::paymentMethodForAdvertiser();
       return view('admin.advertiser.add_new_transaction',['payment_method' => $payment_method]);
    }

    public function saveTransactionNumber(TransactionNumberRequest $transactionNumberRequest)
    {
        TransactionNumberRepository::createTransaction($transactionNumberRequest);
        return back()->with('success_notification','transaction number added successfully');
    }

    public function editTransactionPage($transaction_id)
    {
       $transaction = TransactionNumberRepository::findTransaction($transaction_id);
       if($transaction->used_status){
           return back()->with('error_notification','transaction is used can not be edited');
       }
       $payment_method =  ListOfPaymentMethodRepository::paymentMethodForAdvertiser();
       return view('admin.advertiser.edit_transaction',compact(['transaction','payment_method']));
    }

    public function editTransaction(EditTransactionRequest $editTransactionRequest, $transaction_id)
    {
       if(!$this->checkTransactionNumberDuplication($transaction_id,$editTransactionRequest->transaction_number)){
         return back()->with('error_notification','transaction number must be unique');
       }elseif(TransactionNumberRepository::isTransactionNumberUsed($transaction_id)){
         return back()->with('error_notification','transaction is used can not be edited');
       }
        TransactionNumberRepository::updateTransaction($transaction_id,$editTransactionRequest);
        return redirect()->route('admin.transaction_numbers.edit_transaction_page',[$transaction_id])->with('success_notification','transaction updated successfully');
    }

    private function checkTransactionNumberDuplication($transaction_id,$transaction_number)
    {
        $transaction = TransactionNumberRepository::findTransaction($transaction_id);
        if($transaction->ref_number !== $transaction_number){
           if(TransactionNumberRepository::isTransactionExist($transaction_number)){
               return false;
           }
           return true;
        }
        return true;
    }

    public function deleteTransaction($transaction_id)
    {
      if(TransactionNumberRepository::isTransactionNumberUsed($transaction_id)){
        return back()->with('error_notification','transaction is used can not be deleted');
      }
        TransactionNumberRepository::deleteTransaction($transaction_id);
        return back()->with('success_notification','transaction  deleted successfully');
    }
}
