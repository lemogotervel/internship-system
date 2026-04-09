<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportComment;
use App\Models\Placement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $reports = match($user->role) {
            'student' =>$user->student ? Report::where('student_id', $user->student->id)->latest()->get(): collect(),
            'academic_supervisor' => $this->supervisorReports($user),
            'coordinator' => Report::with('student', 'placement')->latest()->get(),
            default => collect(),
        };

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $student    = Auth::user()->student;
        $placements = Placement::where('student_id', $student->id)->where('status', 'approved')->get();
        return view('reports.create', compact('placements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'placement_id' => 'required|exists:placements,id',
            'title'        => 'required|string|max:200',
            'content'      => 'nullable|string',
            'due_date'     => 'required|date',
            'file'         => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('reports', 'public');
        }

        Report::create([
            'student_id'   => Auth::user()->student->id,
            'placement_id' => $data['placement_id'],
            'title'        => $data['title'],
            'content'      => $data['content'] ?? null,
            'due_date'     => $data['due_date'],
            'file_path'    => $filePath,
            'status'       => 'draft',
        ]);

        return redirect()->route('reports.index')->with('success', 'Report creared.');
    }

    public function show(Report $report)
    {
        $report->load('student.user', 'placement', 'comments.user', 'reviewer');
        return view('reports.show', compact('report'));
    }

    public function submit(Report $report)
    {
        abort_if($report->student_id !== Auth::user()->student->id, 403);
        $report->update(['status' => 'submitted', 'submitted_at' => now()]);
        return back()->with('success', 'Repport submitted successfully.');
    }

    public function review(Request $request, Report $report)
    {
        $data = $request->validate([
            'action'  => 'required|in:approve,reject',
            'comment' => 'nullable|string',
        ]);

        $report->update([
            'status'      => $data['action'] === 'approve' ? 'approved' : 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        if (!empty($data['comment'])) {
            ReportComment::create([
                'report_id' => $report->id,
                'user_id'   => Auth::id(),
                'comment'   => $data['comment'],
            ]);
        }

        return back()->with('success', 'Repport evaluated.');
    }

    public function comment(Request $request, Report $report)
    {
        $request->validate(['comment' => 'required|string|max:1000']);
        ReportComment::create([
            'report_id' => $report->id,
            'user_id'   => Auth::id(),
            'comment'   => $request->comment,
        ]);
        return back()->with('success', 'Comment added.');
    }

    private function supervisorReports($user)
    {
        $supervisor = $user->supervisor;
        return Report::whereIn('student_id',
            DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->pluck('student_id')
        )->with('student', 'placement')->latest()->get();
    }
}