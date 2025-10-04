<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function dataBarang()
    {
        $barangs = Barang::all();
        return view('owner.data-barang', compact('barangs'));
    }

    public function dataCustomer()
    {
        $customers = Customer::all();
        return view('owner.data-customer', compact('customers'));
    }
}
