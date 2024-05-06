<?php


namespace App\Services;


use App\Constants\TransactionType;
use App\Models\Transaction;
use Carbon\Carbon;

class DepositService
{

    public function depositStore(array $data)
    {

        $user = auth()->user();

        $depositTransaction = new Transaction();
        $depositTransaction->user_id = $user->id;
        $depositTransaction->amount = $data['amount'];;
        $depositTransaction->transaction_type = TransactionType::DEPOSIT;
        $depositTransaction->date = Carbon::now();
        $depositTransaction->save();

        $user->balance += $data['amount'];;
        $user->save();

        return $depositTransaction;
    }

}
