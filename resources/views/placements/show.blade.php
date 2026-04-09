@extends('layouts.app')
@section('title', 'Placement details')
@section('page-title', 'Placement')

@section('content')
@php
$statusConfig = [
    'pending'  => ['label'=>'pending', 'class'=>'bg-amber-100 text-amber-700'],
    'approved' => ['label'=>'Approuved',   'class'=>'bg-emerald-100 text-emerald-700'],
    'rejected' => ['label'=>'Rejected',     'class'=>'bg-red-100 text-red-700'],
];
$s = $statusConfig[$placement->status];
@endphp

<div class="max-w-3xl space-y-6">

    {{-- Header card --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $placement->company_name }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $placement->company_address }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $s['class'] }}">{{ $s['label'] }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-0.5">Student</p>
                <p class="font-medium text-slate-800">{{ $placement->student->full_name }}</p>
                <p class="text-xs text-slate-400">{{ $placement->student->student_number }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-0.5">Période</p>
                <p class="font-medium text-slate-800">{{ $placement->start_date->format('d/m/Y') }} → {{ $placement->end_date->format('d/m/Y') }}</p>
                <p class="text-xs text-slate-400">{{ $placement->start_date->diffInWeeks($placement->end_date) }} Weeks</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-0.5">Company supervisor</p>
                <p class="font-medium text-slate-800">{{ $placement->company_supervisor_name }}</p>
                @if($placement->company_supervisor_email)
                <p class="text-xs text-slate-400">{{ $placement->company_supervisor_email }}</p>
                @endif
            </div>
            @if($placement->description)
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-0.5">Description</p>
                <p class="text-slate-700 text-xs">{{ $placement->description }}</p>
            </div>
            @endif
        </div>

        @if($placement->rejection_reason)
        <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-red-700 mb-1">Reason for rejection</p>
            <p class="text-sm text-red-700">{{ $placement->rejection_reason }}</p>
        </div>
        @endif
    </div>

    {{-- Coordinator validation panel --}}
    @if(auth()->user()->isCoordinator() && $placement->isPending())
    <div class="bg-white rounded-2xl border border-amber-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">validation decision</h3>
        <form method="POST" action="{{ route('placements.validate', $placement) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Rejection Motif (if applicable)</label>
                <textarea name="rejection_reason" rows="2"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder="Précisez la raison du rejet..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" name="action" value="approve"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                    ✓ Approuved
                </button>
                <button type="submit" name="action" value="reject"
                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                    ✕ Rejected
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Supervisors --}}
    @if($placement->supervisors->count())
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Assigned supervisor</h3>
        <div class="space-y-3">
            @foreach($placement->supervisors as $supervisor)
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm">
                    {{ strtoupper(substr($supervisor->full_name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-900">{{ $supervisor->full_name }}</p>
                    <p class="text-xs text-slate-500">{{ $supervisor->pivot->type === 'academic' ? 'Academic supervisor' : 'Professional supervisor' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Reports summary --}}
    @if($placement->reports->count())
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-3">Repports ({{ $placement->reports->count() }})</h3>
        <div class="space-y-2">
            @foreach($placement->reports as $report)
            <a href="{{ route('reports.show', $report) }}"
               class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors">
                <span class="text-sm font-medium text-slate-700">{{ $report->title }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full
                    @if($report->status==='approved') bg-emerald-100 text-emerald-700
                    @elseif($report->status==='rejected') bg-red-100 text-red-700
                    @elseif($report->status==='submitted') bg-blue-100 text-blue-700
                    @else bg-slate-100 text-slate-600 @endif">
                    {{ ucfirst($report->status) }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Certificate --}}
    @if($placement->certificate)
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-3">Internship certificate</h3>
        <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl">
            <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <div>
                <p class="text-sm font-medium text-emerald-800">{{ $placement->certificate->original_filename }}</p>
                <a href="{{ Storage::url($placement->certificate->file_path) }}" target="_blank"
                   class="text-xs text-emerald-600 hover:underline">Download file</a>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection