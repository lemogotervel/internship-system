<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use App\Models\Student;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Each supervisor with their student count and details
        $supervisors = Supervisor::with('user')
            ->get()
            ->map(function ($supervisor) {
                $studentIds = DB::table('supervisor_student')
                    ->where('supervisor_id', $supervisor->id)
                    ->pluck('student_id');

                $students = Student::whereIn('id', $studentIds)
                    ->with('user', 'placements')
                    ->get();

                $capacity     = 10;
                $assigned     = $studentIds->count();
                $available    = max(0, $capacity - $assigned);
                $loadPercent  = min(100, round(($assigned / $capacity) * 100));

                return [
                    'supervisor'   => $supervisor,
                    'students'     => $students,
                    'assigned'     => $assigned,
                    'available'    => $available,
                    'capacity'     => $capacity,
                    'load_percent' => $loadPercent,
                    'is_full'      => $assigned >= $capacity,
                    'type'         => $supervisor->type,
                ];
            });

        $academicSupervisors      = $supervisors->where('type', 'academic')->values();
        $professionalSupervisors  = $supervisors->where('type', 'professional')->values();

        // Global stats
        $totalStudents   = Student::count();
        $totalPlacements = Placement::where('status', 'approved')->count();
        $avgLoad         = $supervisors->count() > 0
            ? round($supervisors->avg('assigned'), 1)
            : 0;

        return view('statistics.index', compact(
            'academicSupervisors',
            'professionalSupervisors',
            'totalStudents',
            'totalPlacements',
            'avgLoad'
        ));
    }
}