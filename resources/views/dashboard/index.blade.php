@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $roleLabels = ['student'=>'Student','academic_supervisor'=>'Academic Supervisor','professional_supervisor'=>'Professional Supervisor','coordinator'=>'Internship Coordinator'];
@endphp

<p class="text-slate-500 text-sm mb-6">Welcome, <span class="font-medium text-slate-800">{{ $user->username }}</span> — {{ $roleLabels[$user->role] ?? '' }}</p>

{{-- STUDENT DASHBOARD --}}
@if($user->isStudent())
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['label'=>'Active placement', 'value'=> $stats['placement'] ? 'Yes' : 'None', 'color'=>'blue', 'icon'=>'🏢'],
            ['label'=>'Pending report', 'value'=> $stats['reports_pending'], 'color'=>'amber', 'icon'=>'📄'],
            ['label'=>'Total tasks', 'value'=> $stats['tasks_total'], 'color'=>'purple', 'icon'=>'✅'],
            ['label'=>'Late report', 'value'=> $stats['late_reports'], 'color'=>'red', 'icon'=>'⚠️'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">{{ $card['icon'] }}</div>
        <p class="text-2xl font-bold text-slate-900">{{ $card['value'] }}</p>
        <p class="text-sm text-slate-500 mt-0.5">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

@if($stats['placement'])
<div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">
    <h3 class="font-semibold text-slate-900 mb-3">Internship going on</h3>
    <div class="grid grid-cols-2 gap-3 text-sm">
        <div><span class="text-slate-500">Company :</span> <span class="font-medium">{{ $stats['placement']->company_name }}</span></div>
        <div><span class="text-slate-500">Status :</span>
            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Approuved</span>
        </div>
        <div><span class="text-slate-500">Starting :</span> <span class="font-medium">{{ $stats['placement']->start_date->format('d/m/Y') }}</span></div>
        <div><span class="text-slate-500">End :</span> <span class="font-medium">{{ $stats['placement']->end_date->format('d/m/Y') }}</span></div>
    </div>
    <a href="{{ route('placements.show', $stats['placement']) }}" class="mt-4 inline-flex items-center text-sm text-primary-600 font-medium hover:underline">View details →</a>
</div>
@else
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
    <p class="text-blue-800 text-sm font-medium">You don't have an approved placement yet.</p>
    <a href="{{ route('placements.create') }}" class="mt-2 inline-flex items-center gap-1 text-sm text-blue-700 font-semibold hover:underline">+ Submit internship placement</a>
</div>
@endif

{{-- Quick actions --}}
<div class="grid grid-cols-3 gap-4">
    <a href="{{ route('placements.create') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-lg">🏢</div>
        <span class="text-sm font-medium text-slate-700">New internship</span>
    </a>
    <a href="{{ route('reports.create') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-lg">📄</div>
        <span class="text-sm font-medium text-slate-700">New Repport</span>
    </a>
    <a href="{{ route('tasks.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-lg">✅</div>
        <span class="text-sm font-medium text-slate-700">My Tasks</span>
    </a>
</div>


{{-- COORDINATOR DASHBOARD --}}
@elseif($user->isCoordinator())
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $cards = [
            ['label'=>'Sudent registered','value'=>$stats['total_students'],'color'=>'blue','icon'=>'👩‍🎓'],
            ['label'=>'Pending placement','value'=>$stats['placements_pending'],'color'=>'amber','icon'=>'⏳'],
            ['label'=>'Approved placements','value'=>$stats['placements_approved'],'color'=>'green','icon'=>'✅'],
            ['label'=>'Report submitted','value'=>$stats['reports_submitted'],'color'=>'purple','icon'=>'📋'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">{{ $card['icon'] }}</div>
        <p class="text-2xl font-bold text-slate-900">{{ $card['value'] }}</p>
        <p class="text-sm text-slate-500 mt-0.5">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>
<div class="grid grid-cols-2 gap-4">
    <a href="{{ route('placements.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-lg">⏳</div>
        <div><p class="text-sm font-medium text-slate-700">Validate internships</p><p class="text-xs text-slate-500">{{ $stats['placements_pending'] }} waiting</p></div>
    </a>
    <a href="{{ route('supervisors.assign') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-lg">👤</div>
        <div><p class="text-sm font-medium text-slate-700">Assign supervisors</p><p class="text-xs text-slate-500">Manage Assignments</p></div>
    </a>
    <a href="{{ route('reports.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-lg">📋</div>
        <div><p class="text-sm font-medium text-slate-700">Repports submitted</p><p class="text-xs text-slate-500">{{ $stats['reports_submitted'] }} to be treated</p></div>
    </a>
    <a href="{{ route('supervisors.create') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-lg">➕</div>
        <div><p class="text-sm font-medium text-slate-700">Add a supervisor</p><p class="text-xs text-slate-500">Academic or Professional</p></div>
    </a>
</div>


{{-- PROFESSIONAL SUPERVISOR DASHBOARD --}}
@elseif($user->isProfessionalSupervisor())
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">👩‍🎓</div>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['students_count'] }}</p>
        <p class="text-sm text-slate-500">Student followed up</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">📋<mxGraphModel><root><mxCell id="0"/><mxCell id="1" parent="0"/><mxCell id="2" value="" style="image;image=img/lib/atlassian/Bitbucket_Logo.svg;html=1;" vertex="1" parent="1"><mxGeometry x="330" y="250" width="107" height="120" as="geometry"/></mxCell></root></mxGraphModel></div>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['tasks_created'] }}</p>
        <p class="text-sm text-slate-500">Tasks created</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">⭐</div>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['tasks_evaluated'] }}</p>
        <p class="text-sm text-slate-500">Tasks evaluated</p>
    </div>
</div>
<div class="grid grid-cols-2 gap-4">
    <a href="{{ route('tasks.create') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-lg">➕</div>
        <span class="text-sm font-medium text-slate-700">Create a task</span>
    </a>
    <a href="{{ route('certificates.create') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-lg">📜</div>
        <span class="text-sm font-medium text-slate-700">Upload a certificate</span>
    </a>
</div>


{{-- ACADEMIC SUPERVISOR DASHBOARD --}}
@elseif($user->isAcademicSupervisor())
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">👩‍🎓</div>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['students_count'] }}</p>
        <p class="text-sm text-slate-500">Student followed-up</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">📄</div>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['reports_pending'] }}</p>
        <p class="text-sm text-slate-500">Repports to be evaluated</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-2xl mb-2">✅</div>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['tasks_count'] }}</p>
        <p class="text-sm text-slate-500">My students tasks</p>
    </div>
</div>
<div class="grid grid-cols-2 gap-4">
    <a href="{{ route('reports.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-lg">📄</div>
        <span class="text-sm font-medium text-slate-700">Evaluate report</span>
    </a>
    <a href="{{ route('tasks.index') }}" class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-5 flex items-center gap-3 transition-colors">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-lg">✅</div>
        <span class="text-sm font-medium text-slate-700">follow-up tasks</span>
    </a>
</div>
@endif

@endsection