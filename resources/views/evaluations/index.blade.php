@extends('layouts.app')
@section('title', 'Evaluations')
@section('page-title', 'Final Evaluations')

@section('content')

@if($evaluations->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="text-5xl mb-3">⭐</div>
    <p class="text-slate-500 text-sm">No evaluation for the moment.</p>
</div>
@else
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                @if(!auth()->user()->isStudent())
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                @endif
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Placement</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Note</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Grade</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Evaluated by</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($evaluations as $eval)
            <tr class="hover:bg-slate-50 transition-colors">
                @if(!auth()->user()->isStudent())
                <td class="px-5 py-4 font-medium text-slate-900">{{ $eval->student->full_name }}</td>
                @endif
                <td class="px-5 py-4 text-slate-700">{{ $eval->placement->company_name }}</td>
                <td class="px-5 py-4">
                    <span class="text-lg font-bold
                        @if($eval->grade >= 16) text-emerald-600
                        @elseif($eval->grade >= 12) text-blue-600
                        @elseif($eval->grade >= 10) text-amber-600
                        @else text-red-600 @endif">
                        {{ $eval->grade }}
                    </span>
                    <span class="text-slate-400 text-sm">/20</span>
                </td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                        @if($eval->grade >= 16) bg-emerald-100 text-emerald-700
                        @elseif($eval->grade >= 12) bg-blue-100 text-blue-700
                        @elseif($eval->grade >= 10) bg-amber-100 text-amber-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ $eval->mention ?? '—' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-slate-600">{{ $eval->evaluator->username }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(auth()->user()->isCoordinator() || auth()->user()->isAcademicSupervisor())
<div class="mt-5">
    <p class="text-sm text-slate-500">To evaluate a student, rendez-vous on the approved placement sheet.</p>
</div>
@endif
@endif
@endsection