<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Placement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    //
     public function index()
    {
        $user = Auth::user();
 
        $certificates = match($user->role) {
            'student'                 => Certificate::where('student_id', $user->student->id)->with('placement')->get(),
            'professional_supervisor' => $this->supervisorCertificates($user),
            'coordinator'             => Certificate::with('student', 'placement', 'uploader')->get(),
            default                   => collect(),
        };
 
        return view('certificates.index', compact('certificates'));
    }
 
    public function create()
    {
        $user       = Auth::user();
        $supervisor = $user->supervisor;
 
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->where('type', 'professional')->pluck('student_id')
            : collect();
 
        $placements = Placement::whereIn('student_id', $studentIds)
            ->where('status', 'approved')
            ->with('student')
            ->get();
 
        return view('certificates.create', compact('placements'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'placement_id' => 'required|exists:placements,id',
            'file'         => 'required|file|mimes:pdf|max:5120',
        ]);
 
        $placement = Placement::findOrFail($data['placement_id']);
        $path      = $request->file('file')->store('certificates', 'public');
 
        Certificate::updateOrCreate(
            ['placement_id' => $placement->id],
            [
                'student_id'        => $placement->student_id,
                'uploaded_by'       => Auth::id(),
                'file_path'         => $path,
                'original_filename' => $request->file('file')->getClientOriginalName(),
            ]
        );
 
        return redirect()->route('certificates.index')->with('success', 'Certificate uploaded successfully.');
    }
 
    private function supervisorCertificates($user)
    {
        $supervisor = $user->supervisor;
        $studentIds = $supervisor
            ? DB::table('supervisor_student')->where('supervisor_id', $supervisor->id)->pluck('student_id')
            : collect();
        return Certificate::whereIn('student_id', $studentIds)->with('student', 'placement')->get();
    }
}
