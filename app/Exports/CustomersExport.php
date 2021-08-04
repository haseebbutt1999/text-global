<?php

namespace App\Exports;

use App\Customer;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\View;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
{
    public function view(): View
    {
        return view('exports.customer_data', [
            'customer_data' => Customer::where('user_id', Auth::user()->id)->select(['first_name', 'last_name', 'email', 'phone','orders_count','note', 'currency','total_spent', 'created_at'])->get(),
        ]);
    }
}
