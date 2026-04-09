<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use App\Models\Report;
use App\Models\Task;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
 
        $stats = match($user->role) {
            'student' => $this->studentStats($user),
            'academic_supervisor' => $this->academicSupervisorStats($user),
            'professional_supervisor' => $this->professionalSupervisorStats($user),
            'coordinator' => $this->coordinatorStats(),
            default => [],
        };
 
        return view('dashboard.index', compact('stats', 'user'));
    }
 
    private function studentStats($user): array
    {
        $student  = $user->student;
        $placement = $student?->activePlacement();
        return [
            'placement'        => $placement,
            'reports_pending'  => $student ? Report::where('student_id', $student->id)->where('status','draft')->count() : 0,
            'tasks_total'      => $student ? Task::where('student_id', $student->id)->count() : 0,
            'tasks_done'       => $student ? Task::where('student_id', $student->id)->where('status','completed')->count() : 0,
            'late_reports'     => $student ? Report::where('student_id', $student->id)->where('status','draft')->where('due_date','<',now())->count() : 0,
        ];
    }
 
    private function academicSupervisorStats($user): array
    {
        $supervisor = $user->supervisor;
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->pluck('student_id')
            : collect();
 
        return [
            'students_count'   => $studentIds->count(),
            'reports_pending'  => Report::whereIn('student_id', $studentIds)->where('status','submitted')->count(),
            'tasks_count'      => Task::whereIn('student_id', $studentIds)->count(),
        ];
    }
 
    private function professionalSupervisorStats($user): array
    {
        $supervisor = $user->supervisor;
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->pluck('student_id')
            : collect();
 
        return [
            'students_count'  => $studentIds->count(),
            'tasks_created'   => Task::where('created_by', $user->id)->count(),
            'tasks_evaluated' => \App\Models\TaskEvaluation::where('evaluated_by', $user->id)->count(),
        ];
    }
 
    private function coordinatorStats(): array
    {
        return [
            'total_students'         => Student::count(),
            'placements_pending'     => Placement::where('status','pending')->count(),
            'placements_approved'    => Placement::where('status','approved')->count(),
            'reports_submitted'      => Report::where('status','submitted')->count(),
        ];
    }
}
