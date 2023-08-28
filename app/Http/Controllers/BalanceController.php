<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function addFunds(Request $request, User $user): RedirectResponse
    {
        $amount      = $request->input('amount');
        $description = 'Зачисление средств';

        $user->credit($amount, $description);

        return redirect()->back()->with('status', 'Средства успешно зачислены!');
    }

    public function makePurchase(Request $request, User $user): RedirectResponse
    {
        $amount      = $request->input('amount');
        $description = 'Покупка товара';

        try {
            $user->debit($amount, $description);
            return redirect()->back()->with('status', 'Покупка успешно совершена!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

