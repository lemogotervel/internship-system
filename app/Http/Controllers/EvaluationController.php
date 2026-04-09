<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Placement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    //
     public function index()
    {
        $user = Auth::user();
 
        $evaluations = match($user->role) {
            'student'     => Evaluation::where('student_id', $user->student->id)->with('placement', 'evaluator')->get(),
            'coordinator' => Evaluation::with('student', 'placement', 'evaluator')->latest()->get(),
            'academic_supervisor' => $this->supervisorEvaluations($user),
            
            default       => collect(),
        };
 
        return view('evaluations.index', compact('evaluations'));
    }
 
    public function create(Placement $placement)
    {
        return view('evaluations.create', compact('placement'));
    }
 
    public function store(Request $request, Placement $placement)
    {
        $data = $request->validate([
            'grade'    => 'required|numeric|min:0|max:20',
            'mention'  => 'nullable|string|max:50',
            'comments' => 'nullable|string',
        ]);
 
        Evaluation::updateOrCreate(
            ['student_id' => $placement->student_id, 'placement_id' => $placement->id],
            [...$data, 'evaluated_by' => Auth::id()]
        );
 
        return redirect()->route('evaluations.index')->with('success', 'Evaluation registered.');
    }
 
    private function supervisorEvaluations($user)
    {
        $supervisor = $user->supervisor;
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->pluck('student_id')
            : collect();
        return Evaluation::whereIn('student_id', $studentIds)->with('student', 'placement', 'evaluator')->get();
    }
}
