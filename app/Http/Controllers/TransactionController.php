<?php

namespace App\Http\Controllers;

use App\Constants\TransactionType;
use App\Http\Requests\DepositReqeust;
use App\Http\Requests\WithdrawalRequest;
use App\Models\Transaction;
use App\Services\DepositService;
use App\Services\TransactionService;
use App\Services\WithdrawalService;

class TransactionController extends Controller
{

    public function index(TransactionService $transactionService)
    {

        $allTransaction = $transactionService->getAllTransactionAndBalance(auth()->user());
        $balance = auth()->user()->balance;

        return view('show-transaction-balance',compact('allTransaction','balance'));
    }


    public function showDepositedTransactions(TransactionService $transactionService)
    {

        $allDepositTransaction = $transactionService->getAllDepositTransaction(auth()->user());

        return view('show-deposit-transaction',compact('allDepositTransaction'));

    }


    public function deposit(DepositReqeust $request,TransactionService $transactionService)
    {

        $validated = $request->validated();

        $deposit = $transactionService->addDeposit($validated);

        return back()->with('success','Deposit successful');
    }


    public function showWithdrawalTransactions(TransactionService $transactionService)
    {

        $allWithdrawalTransaction = $transactionService->getAllWithdrawalTransaction(auth()->user());

        return view('show-withdrawal-transaction',compact('allWithdrawalTransaction'));
    }


    public function withdrawal(WithdrawalRequest $request,TransactionService $transactionService)
    {
        $validated = $request->validated();

        $withdrawal = $transactionService->withdrawalTransaction($validated);

        if (isset($withdrawal['error'])) {
            return back()->with('error', $withdrawal['error']);
        }

        return back()->with('success','Money withdraw successful');
    }
}
