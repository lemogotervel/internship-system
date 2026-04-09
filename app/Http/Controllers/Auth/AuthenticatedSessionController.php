<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    
     public function showLogin()
    {
        return view('auth.login');
    }
 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
 
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
 
        return back()->withErrors(['username' => 'Identifiants incorrects.'])->withInput();
    }
 
    public function showRegister()
    {
        return view('auth.register');
    }
 
    public function register(Request $request)
    {
        $data = $request->validate([
            'username'       => 'required|string|unique:users',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|string|min:6|confirmed',
            'full_name'      => 'required|string|max:100',
            'student_number' => 'required|string|unique:students',
            'program'        => 'required|string|max:100',
            'level'          => 'nullable|string|max:20',
            'phone'          => 'nullable|string|max:20',
        ]);
 
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'student',
        ]);
 
        Student::create([
            'user_id'        => $user->id,
            'full_name'      => $data['full_name'],
            'student_number' => $data['student_number'],
            'program'        => $data['program'],
            'level'          => $data['level'] ?? null,
            'phone'          => $data['phone'] ?? null,
        ]);
 
        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Registration successful. Welcome !');
    }
 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

    /*public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /*public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    /*public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
