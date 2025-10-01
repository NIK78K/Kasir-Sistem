<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'tipe_pembeli' => 'required|in:pembeli,bengkel,langganan',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Customer::create($request->only('nama_customer', 'alamat', 'tipe_pembeli', 'no_hp'));

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan');
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'tipe_pembeli' => 'required|in:pembeli,bengkel,langganan',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $customer->update($request->only('nama_customer', 'alamat', 'tipe_pembeli', 'no_hp'));

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus');
    }
}
