<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use App\Models\Student;
use App\Models\Placement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class SupervisorController extends Controller
{
    //
     public function index()
    {
        $supervisors = Supervisor::with('user')->latest()->get();
        return view('supervisors.index', compact('supervisors'));
    }
 
    public function create()
    {
        return view('supervisors.create');
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'username'   => 'required|string|unique:users',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:6',
            'full_name'  => 'required|string|max:100',
            'type'       => 'required|in:academic,professional',
            'company'    => 'nullable|string|max:150',
            'department' => 'nullable|string|max:150',
            'phone'      => 'nullable|string|max:20',
        ]);
 
        $role = $data['type'] === 'academic' ? 'academic_supervisor' : 'professional_supervisor';
 
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $role,
        ]);
 
        Supervisor::create([
            'user_id'    => $user->id,
            'full_name'  => $data['full_name'],
            'type'       => $data['type'],
            'company'    => $data['company'] ?? null,
            'department' => $data['department'] ?? null,
            'phone'      => $data['phone'] ?? null,
        ]);
 
        return redirect()->route('supervisors.index')->with('success', 'Supervisor created.');
    }
 
    public function assign(Request $request)
    {
        $data = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'placement_id'  => 'required|exists:placements,id',
            'supervisor_id' => 'required|exists:supervisors,id',
            'type'          => 'required|in:academic,professional',
        ]);
 
        DB::table('supervisor_student')->updateOrInsert(
            [
                'student_id'    => $data['student_id'],
                'placement_id'  => $data['placement_id'],
                'type'          => $data['type'],
            ],
            ['supervisor_id' => $data['supervisor_id'], 'created_at' => now(), 'updated_at' => now()]
        );
 
        return back()->with('success', 'Supervisor assigned.');
    }
 
    public function assignForm()
    {
        $students    = Student::with('user')->get();
        $supervisors = Supervisor::with('user')->get();
        $placements  = Placement::where('status', 'approved')->with('student')->get();
        return view('supervisors.assign', compact('students', 'supervisors', 'placements'));
    }
}
