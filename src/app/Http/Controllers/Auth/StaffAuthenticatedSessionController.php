<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthenticatedSessionController extends Controller
{
    public function create(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (($user->role ?? null) === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if (($user->role ?? null) === 'owner') {
                return redirect()->route('staff.dashboard');
            }
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.staff-login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ]);

        if (! Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (($user->role ?? null) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (($user->role ?? null) === 'owner') {
            return redirect()->route('staff.dashboard');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors(['email' => 'スタッフ権限がありません。'])
            ->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
