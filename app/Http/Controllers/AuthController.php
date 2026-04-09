<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 

class Authcontroller extends Controller
{
    //
     public function showLogin(Request $request){
        $role = $request->query('role', 'student');

        $validRoles= ['student',' academic_supervisor', 'professional_supervisor', 'coordinator'];

        if (!in_array($role, $validRoles)){
            $role= 'student';
        }
     
        return view('auth.login', compact('role'));
    }

 
    public function login(Request $request)
    {
        // $credentials = $request->validate([
        //     'username' => 'required|string',
        //     'password' => 'required|string',
        // ]);
 
        // if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
        //     $request->session()->regenerate();
        //     return redirect()->route('dashboard');
        
        //  // Redirect based on role — all roles go to dashboard
        //     return redirect()->intended(route('dashboard'));

         $role = $request->input('role', 'student');
 
        return match($role) {
            'student'                 => $this->loginStudent($request),
            'academic_supervisor',
            'professional_supervisor' => $this->loginSupervisor($request),
            'coordinator'             => $this->loginCoordinator($request),
            default                   => back()->withErrors(['role' => 'Rôle inconnu.']),
        };
    }

 
    //     return back()->withErrors(['username' => 'Incorrect Credentials.'])->withInput();
    // }
    
     private function loginStudent(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
 
        $user = User::where('username', $data['username'])
                    ->where('role', 'student')
                    ->first();
 
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()
                ->withInput()
                ->withErrors(['username' => 'User name or password incorrect.']);
        }
 
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    // ── Supervisors: email + password
    private function loginSupervisor(Request $request)
    {
        $role = $request->input('role');
 
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
 
        $user = User::where('email', $data['email'])
                    ->where('role', $role)
                    ->first();
 
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Incorrect email or password.']);
        }
 
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
 
     private function loginCoordinator(Request $request)
    {
        $data = $request->validate([
            'username'         => 'required|string',
            'coordinator_code' => 'required|string',
        ]);
 
        $user = User::where('username', $data['username'])
                    ->where('role', 'coordinator')
                    ->first();
 
        if (!$user || $user->coordinator_code !== $data['coordinator_code']) {
            return back()
                ->withInput()
                ->withErrors(['username' => 'coordinator credentials incorrect.']);
        }
 
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
 
    public function showRegister()
    {
         // If already logged in as non-student, redirect to dashboard
        if (Auth::check() && !$this->user()->isStudent()) {
            return redirect()->route('dashboard');
        }
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
        return redirect()->route('dashboard')->with('success', 'Registered successfully. Wellcome !');
    }
 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
