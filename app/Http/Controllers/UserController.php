<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Mail\UserInvitation;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:kasir,owner',
        ]);

        // Generate temporary password and invitation token
        $temporaryPassword = Str::random(12);
        $invitationToken = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'role' => $request->role,
            'invitation_token' => $invitationToken,
            'invited_at' => now(),
        ]);

        // Send invitation email
        $activationUrl = route('user.activate', $invitationToken);
        Mail::to($user->email)->send(new UserInvitation($user, $temporaryPassword, $activationUrl));

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan dan email undangan telah dikirim.'
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan dan email undangan telah dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Owner cannot change password of other users
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:kasir,owner',
        ];

        if (Auth::user()->role === 'owner' && $user->role !== 'owner') {
            // Owner editing non-owner user: password fields are not allowed
            if ($request->filled('password')) {
                return back()->withErrors(['password' => 'Sebagai owner, Anda tidak dapat mengubah password user.']);
            }
        } else {
            // Allow password change for self or other owners
            $validationRules['password'] = 'nullable|string|min:8|confirmed';
        }

        $request->validate($validationRules);

        // Check if there are any changes
        $hasChanges = false;
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        foreach ($data as $key => $value) {
            $oldValue = $user->$key;
            
            // Normalize values for comparison
            $normalizedOld = $oldValue === null ? null : (string)$oldValue;
            $normalizedNew = $value === null ? null : (string)$value;
            
            if ($normalizedOld !== $normalizedNew) {
                $hasChanges = true;
                break;
            }
        }

        // Check if password is being updated
        if ($request->filled('password') && (Auth::user()->role !== 'owner' || $user->role === 'owner')) {
            $hasChanges = true;
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

            return redirect()->route('user.index')->with('info', 'Tidak ada yang diperbarui');
        }

        $user->update($data);

        if ($request->filled('password') && (Auth::user()->role !== 'owner' || $user->role === 'owner')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.'
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Show the activation form for the invited user.
     */
    public function activate(Request $request, $token): View
    {
        $user = User::where('invitation_token', $token)->first();

        if (!$user) {
            abort(404, 'Token aktivasi tidak valid.');
        }

        return view('auth.activate', ['request' => $request, 'user' => $user, 'token' => $token]);
    }

    /**
     * Activate the user account and set new password.
     */
    public function activateStore(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('invitation_token', $request->token)->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Token aktivasi tidak valid.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'invitation_token' => null,
            'invited_at' => null,
            'email_verified_at' => now(),
        ]);

        // Auto-login user after activation
        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute: false))->with('status', 'Akun berhasil diaktifkan dan Anda telah login.');
    }
}
