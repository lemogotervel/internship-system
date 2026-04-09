<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskEvaluation;
use App\Models\Placement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    //
     public function index()
    {
        $user = Auth::user();
 
        $tasks = match($user->role) {
            'student' => $user->student ? Task::where('student_id', $user->student->id)
                ->with('evaluation', 'placement')->latest()->get():collect(),
 
            'professional_supervisor' => Task::where('created_by', $user->id)
                ->with('student', 'evaluation', 'placement')->latest()->get(),
 
            'academic_supervisor' => $this->supervisorTasks($user),
            'coordinator'         => Task::with('student', 'evaluation', 'placement', 'creator')->latest()->get(),
            default               => collect(),
        };
 
        return view('tasks.index', compact('tasks'));
    }
 
    public function create()
    {
        $user       = Auth::user();
        $supervisor = $user->supervisor;
 
        // Find students assigned to this professional supervisor
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->where('type', 'professional')->pluck('student_id')
            : collect();

   //load students with theri approved placements
        $students = Student::whereIn('id', $studentIds)
            ->with(['user', 'placements' => fn($q) => $q->where('status', 'approved')])
            ->get();
 
            //a list of approved placements for students
             $placements = Placement::whereIn('student_id', $studentIds)
            ->where('status', 'approved')
            ->with('student')
            ->get();

        return view('tasks.create', compact('students','placements'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'placement_id' => 'required|exists:placements,id',
            'title'        => 'required|string|max:200',
            'description'  => 'nullable|string',
            'due_date'     => 'nullable|date',
        ]);
 
        Task::create([
            ...$data,
            'created_by' => Auth::id(),
            'status'     => 'todo',
        ]);
 
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }
 
    public function show(Task $task)
    {
        $task->load('student.user', 'placement', 'evaluation.evaluator', 'creator');
        return view('tasks.show', compact('task'));
    }
 
    public function updateStatus(Request $request, Task $task)
    {
        // Only the assigned student can update status
        abort_if(Auth::user()->student?->id !== $task->student_id, 403);
        $request->validate(['status' => 'required|in:todo,in_progress,completed']);
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
 
    public function evaluate(Request $request, Task $task)
    {
        // Only professional supervisor (creator) can evaluate
        abort_if(Auth::id() !== $task->created_by, 403);
 
        $data = $request->validate([
            'score'    => 'required|numeric|min:0|max:20',
            'feedback' => 'nullable|string|max:1000',
        ]);
 
        TaskEvaluation::updateOrCreate(
            ['task_id' => $task->id],
            [
                'student_id'   => $task->student_id,
                'evaluated_by' => Auth::id(),
                'score'        => $data['score'],
                'feedback'     => $data['feedback'] ?? null,
            ]
        );
 
        return back()->with('success', 'Evaluation registered.');
    }
 
    private function supervisorTasks($user)
    {
        $supervisor = $user->supervisor;
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->pluck('student_id')
            : collect();
        return Task::whereIn('student_id', $studentIds)->with('student', 'evaluation', 'placement', 'creator')->latest()->get();
    }
}
