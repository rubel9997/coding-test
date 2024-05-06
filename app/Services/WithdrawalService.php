<?php


namespace App\Services;


use App\Constants\AccountType;
use App\Constants\TransactionType;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{

    public function withdrawalTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $auth_user = auth()->user();
            $amount = floatval($data['amount']);

            if ($amount > $auth_user->balance) {
                return ['error' =>'Insufficient balance'];
            }

            $fee = $this->calculateFee($auth_user, $amount);

            $amount -= $fee;

            $withdrawTransaction = $this->addWithdraw($auth_user, $amount, $fee);

            $auth_user->balance -= $amount;
            $auth_user->save();

            return ['withdraw' => $withdrawTransaction];
        });
    }


    private function calculateFee($user, $amount)
    {
        $fee = 0;
        $freeWithdrawalAmount = 1000;
        $freeMonthlyWithdrawalLimit = 5000;
        $isFriday = Carbon::now()->isFriday();
        $totalWithdrawnThisMonth = Transaction::where('user_id', $user->id)
            ->where('transaction_type', TransactionType::WITHDRAWAL)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('amount');

        if ($isFriday) {
            return $fee;
        }

        if ($amount > $freeWithdrawalAmount) {
            if ($totalWithdrawnThisMonth > $freeMonthlyWithdrawalLimit) {
                $fee = $amount * ($user->account_type === AccountType::INDIVIDUAL ? 0.015 : 0.025);
            } else {
                $fee = ($amount - $freeWithdrawalAmount) * ($user->account_type === AccountType::INDIVIDUAL ? 0.025 : 0.015);
            }
        }

        if ($user->account_type === AccountType::BUSINESS && $totalWithdrawnThisMonth > 50000) {
            $fee = $amount * 0.015;
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

        return $withdrawTransaction; // Return the created transaction
    }

}
