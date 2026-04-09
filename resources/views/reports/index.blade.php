@extends('layouts.app')
@section('title', 'Raports')
@section('page-title', 'Raports')

@section('header-actions')
@if(auth()->user()->isStudent())
<a href="{{ route('reports.create') }}"
   class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New report
</a>
@endif
@endsection

@section('content')
@php
$statusConfig = [
    'draft'     => ['label'=>'Draft',  'class'=>'bg-slate-100 text-slate-600'],
    'submitted' => ['label'=>'Submitted',     'class'=>'bg-blue-100 text-blue-700'],
    'approved'  => ['label'=>'Approuved',   'class'=>'bg-emerald-100 text-emerald-700'],
    'rejected'  => ['label'=>'Rejected',     'class'=>'bg-red-100 text-red-700'],
];
@endphp

@if($reports->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="text-5xl mb-3">📄</div>
    <p class="text-slate-500 text-sm">No report found.</p>
    @if(auth()->user()->isStudent())
    <a href="{{ route('reports.create') }}" class="mt-3 inline-block text-primary-600 text-sm font-medium hover:underline">Create first report</a>
    @endif
</div>
@else
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                @if(!auth()->user()->isStudent())
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                @endif
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Title</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Due date</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($reports as $report)
            @php $st = $statusConfig[$report->status]; $late = $report->isLate(); @endphp
            <tr class="hover:bg-slate-50 transition-colors {{ $late ? 'bg-red-50/40' : '' }}">
                @if(!auth()->user()->isStudent())
                <td class="px-5 py-4 font-medium text-slate-900">{{ $report->student->full_name }}</td>
                @endif
                <td class="px-5 py-4">
                    <p class="font-medium text-slate-900">{{ $report->title }}</p>
                    @if($late)
                    <span class="text-xs text-red-500 font-medium">⚠ Delayed</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-slate-600 {{ $late ? 'text-red-600 font-medium' : '' }}">
                    {{ $report->due_date->format('d/m/Y') }}
                </td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $st['class'] }}">{{ $st['label'] }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('reports.show', $report) }}"
                       class="text-primary-600 hover:text-primary-800 font-medium text-xs">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection