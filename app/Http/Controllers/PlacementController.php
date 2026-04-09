<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notifiable;
use App\Models\Student;
use App\Models\User;
use App\Notifications\PlacementStatusNotification;
use App\Models\Placement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class PlacementController extends Controller
{
    public function index()
    {
        $user = $this->user();

        $placements = match($user->role) {
            'student' => $user->student
    ? Placement::where('student_id', $user->student->id)->latest()->get()
    : collect(),
            'coordinator' => Placement::with('student')->latest()->get(),
            default       => collect(),
        };

        return view('placements.index', compact('placements'));
    }

    public function create()
    {
        return view('placements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'               => 'required|string|max:150',
            'company_address'            => 'required|string|max:255',
            'company_supervisor_name'    => 'required|string|max:100',
            'company_supervisor_email'   => 'nullable|email|max:100',
            'company_supervisor_phone'   => 'nullable|string|max:20',
            'start_date'                 => 'required|date|after_or_equal:today',
            'end_date'                   => 'required|date|after:start_date',
            'description'                => 'nullable|string',
        ]);

        $data['student_id'] = $this->user()->student->id;
        $data['status']     = 'pending';

        Placement::create($data);


        return redirect()->route('placements.index')->with('success', 'internship placement submitted successfully.');
    }

    public function show(Placement $placement)
    {
        $this->authorizeView($placement);
        $placement->load('student.user', 'supervisors', 'reports', 'certificate');
        return view('placements.show', compact('placement'));
    }

    public function validate(Request $request, Placement $placement)
    {
        $request->validate(['action' => 'required|in:approve,reject', 'rejection_reason' => 'nullable|string']);

        if ($request->action === 'approve') {
            $placement->update([
                'status'       => 'approved',
                'validated_by' => Auth::id(),
                'validated_at' => now(),
            ]);
        } else {
            $placement->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'validated_by'     => Auth::id(),
                'validated_at'     => now(),
            ]);
        }

        return back()->with('success', 'internship status updated.');
    }

    private function authorizeView(Placement $placement): void
    {
        $user = $this->user();
        if ($user->isStudent() && $placement->student_id !== $user->student->id) {
            abort(403);
        }
    }
}