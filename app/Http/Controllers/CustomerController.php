<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_customer', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_hp', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by tipe_pembeli
        if ($request->filled('tipe_pembeli')) {
            $query->where('tipe_pembeli', $request->tipe_pembeli);
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        // Get all available buyer types
        $allTipePembeli = ['pembeli', 'bengkel_langganan'];

        return view('customer.index', compact('customers', 'allTipePembeli'));
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
            'tipe_pembeli' => 'required|in:pembeli,bengkel_langganan',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Customer::create($request->only('nama_customer', 'alamat', 'tipe_pembeli', 'no_hp'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil ditambahkan'
            ]);
        }

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
            'tipe_pembeli' => 'required|in:pembeli,bengkel_langganan',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data = $request->only('nama_customer', 'alamat', 'tipe_pembeli', 'no_hp');

        // Check if there are any changes
        $hasChanges = false;
        foreach ($data as $key => $value) {
            $oldValue = $customer->$key;
            
            // Normalize values for comparison
            $normalizedOld = $oldValue === null ? null : (string)$oldValue;
            $normalizedNew = $value === null ? null : (string)$value;
            
            if ($normalizedOld !== $normalizedNew) {
                $hasChanges = true;
                break;
            }
        }

        // If no changes detected, return appropriate message
        if (!$hasChanges) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada yang diperbarui',
                    'no_changes' => true
                ]);
            }

            return redirect()->route('customer.index')->with('info', 'Tidak ada yang diperbarui');
        }

        $customer->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer berhasil diperbarui'
            ]);
        }

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy(Request $request, Customer $customer)
    {
        // Allow deletion even if customer has related transaksis
        // The reports will still show the data because transaksis are not deleted
        $customer->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Customer berhasil dihapus']);
        }

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus');
    }
}
