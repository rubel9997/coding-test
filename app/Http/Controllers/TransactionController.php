<?php

namespace App\Http\Controllers;

use App\Constants\TransactionType;
use App\Http\Requests\DepositReqeust;
use App\Http\Requests\WithdrawalRequest;
use App\Models\Transaction;
use App\Services\DepositService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function showAllTransactionsAndBalance()
    {
        $auth_user = auth()->user();
        $allTransaction = Transaction::with('user')->where('user_id',$auth_user->id)->paginate(10);;
        return view('show-transaction-balance',compact('allTransaction','auth_user'));
    }

    public function showDepositedTransactions()
    {
        $auth_user = auth()->user();
        $allDepositTransaction = Transaction::where('user_id',$auth_user->id)
            ->where('transaction_type',TransactionType::DEPOSIT)
            ->paginate(10);
        return view('show-deposit-transaction',compact('allDepositTransaction','auth_user'));

    }

    public function deposit(DepositReqeust $request,DepositService $depositService)
    {

        $validated = $request->validated();

        $deposit = $depositService->depositStore($validated);

        return back()->with('success','Deposit successful');
    }

    public function showWithdrawalTransactions()
    {
        $auth_user = auth()->user();
        $allWithdrawalTransaction = Transaction::where('user_id', $auth_user->id)
            ->where('transaction_type', TransactionType::WITHDRAWAL)
            ->paginate(10);
        return view('show-withdrawal-transaction',compact('allWithdrawalTransaction','auth_user'));
    }

    public function withdrawal(WithdrawalRequest $request,WithdrawalService $withdrawalService)
    {
        $validated = $request->validated();

        $withdrawal = $withdrawalService->withdrawalTransaction($validated);

        if (isset($withdrawal['error'])) {
            return back()->with('error', $withdrawal['error']);
        }

        return back()->with('success','Money withdraw successful');
    }
}
