<?php


namespace App\Services;


use App\Constants\AccountType;
use App\Constants\TransactionType;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionService
{

    public function getAllTransactionAndBalance($user)
    {

        return Cache::remember('all-transaction-page-'.request('page',1), Carbon::now()->addMinutes(5), function () use($user) {

            $transactions = Transaction::with('user')->where('user_id',$user->id)->orderByDesc('date')->paginate(10);
            $balance = auth()->user()->balance;

            return compact('transactions', 'balance');
        });

    }

    public function getAllDepositTransaction($user)
    {
        return Cache::remember('all-deposit-transaction-page-'.request('page',1), Carbon::now()->addMinutes(5), function () use($user) {

            $depositTransaction = Transaction::where('user_id', $user->id)->where('transaction_type', TransactionType::DEPOSIT)->orderByDesc('date')->paginate(10);

            return compact('depositTransaction');

        });
    }

    public function getAllWithdrawalTransaction($user)
    {

        return Cache::remember('all-withdrawal-transaction-page-'.request('page',1), Carbon::now()->addMinutes(5), function () use($user) {

            $withdrawalTransaction = Transaction::where('user_id', $user->id)->where('transaction_type', TransactionType::WITHDRAWAL)->orderByDesc('date')->paginate(10);;

            return compact('withdrawalTransaction');

        });

    }


    public function addDeposit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();

            $depositTransaction = new Transaction();
            $depositTransaction->user_id = $user->id;
            $depositTransaction->amount = floatval($data['amount']);
            $depositTransaction->transaction_type = TransactionType::DEPOSIT;
            $depositTransaction->date = Carbon::now();
            $depositTransaction->save();

            $user->balance += $data['amount'];
            $user->save();

//            Cache::forget('all_transaction_data_'.$user->id);
//            Cache::forget('all_deposit_transaction_data_'.$user->id);

            return $depositTransaction;
        });
    }


    public function withdrawalTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {

            $auth_user = auth()->user();
            $amount = floatval($data['amount']);

            if ($amount > $auth_user->balance) {
                return ['error' =>'Insufficient balance'];
            }

            $fee = $this->calculateFee($auth_user, $amount);

            $withdrawTransaction = $this->addWithdraw($auth_user, $amount, $fee);

            $auth_user->balance -= $amount + $fee;
            $auth_user->save();

//            Cache::forget('all_transaction_data_'.$auth_user->id);
//            Cache::forget('all_withdrawal_transaction_data_'.$auth_user->id);

            return ['withdraw' => $withdrawTransaction];
        });
    }


    private function calculateFee($user, $amount)
    {
        $fee = 0;
        $freeWithdrawalAmount = 1000;
        $freeMonthlyWithdrawalLimit = 5000;
        $currentDate = Carbon::now();
        $isFriday = $currentDate->isFriday();

        $totalWithdrawnThisMonth = Transaction::where('user_id', $user->id)
            ->where('transaction_type', TransactionType::WITHDRAWAL)
            ->whereYear('date', $currentDate->year)
            ->whereMonth('date', $currentDate->month)
            ->sum('amount');

        if ($user->account_type === AccountType::INDIVIDUAL) {

            if ($isFriday || $amount <= $freeWithdrawalAmount) {
                return $fee;
            }

            $remainingAmount = $amount - $freeWithdrawalAmount;

            if ($remainingAmount <= 0) {
                return $fee;
            }

            if ($totalWithdrawnThisMonth <= $freeMonthlyWithdrawalLimit) {

                if ($remainingAmount <= $freeMonthlyWithdrawalLimit) {
                    $fee = $remainingAmount * 0.015;
                }

            } else {
                $fee = $amount * 0.015;
            }


        } elseif ($user->account_type === AccountType::BUSINESS) {

            $totalWithdrawn = Transaction::where('user_id', $user->id)
                ->where('transaction_type', TransactionType::WITHDRAWAL)
                ->sum('amount');

            if ($totalWithdrawn > 50000) {
                $fee = $amount * 0.015;

            } else {
                $fee = $amount * 0.025;

            }
        }

        return $fee;
    }


    private function addWithdraw($user, $amount, $fee)
    {
        $withdrawTransaction = new Transaction();
        $withdrawTransaction->user_id = $user->id;
        $withdrawTransaction->amount = $amount;
        $withdrawTransaction->fee = $fee;
        $withdrawTransaction->transaction_type = TransactionType::WITHDRAWAL;
        $withdrawTransaction->date = Carbon::now();
        $withdrawTransaction->save();

        return $withdrawTransaction;
    }

}
