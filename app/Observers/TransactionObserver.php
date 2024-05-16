<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{

    private function clearCache(){

        for($i=1; $i <= 100; $i++){

            $allTransaction = "all-transaction-page-".$i;
            $allDepositTransaction = "all-deposit-transaction-page-".$i;
            $allWithdrawalTransaction = "all-withdrawal-transaction-page-".$i;

            if (Cache::has($allTransaction)) {
                Cache::forget($allTransaction);
            }
            if (Cache::has($allDepositTransaction)) {
                Cache::forget($allDepositTransaction);
            }
            if (Cache::has($allWithdrawalTransaction)) {
                Cache::forget($allWithdrawalTransaction);
            }

        }
    }


    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
