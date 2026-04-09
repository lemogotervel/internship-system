@extends('layouts.app')
@section('title', 'Tasks')
@section('page-title', 'Tasks')

@section('header-actions')
@if(auth()->user()->isProfessionalSupervisor())
<a href="{{ route('tasks.create') }}"
   class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New task
</a>
@endif
@endsection

@section('content')

@if(auth()->user()->isAcademicSupervisor() || auth()->user()->isCoordinator())
<div class="mb-5 flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5 text-sm text-blue-700">
    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
    — Tasks are created and evaluated by professional supervisors.
</div>
@endif

@if($tasks->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="text-5xl mb-3">✅</div>
    <p class="text-slate-500 text-sm">No task found.</p>
    @if(auth()->user()->isProfessionalSupervisor())
    <a href="{{ route('tasks.create') }}" class="mt-3 inline-block text-primary-600 text-sm font-medium hover:underline">Create first task</a>
    @endif
</div>
@else

{{-- Kanban-style board for student --}}
@if(auth()->user()->isStudent())
<div class="grid grid-cols-3 gap-5">
    @foreach(['todo'=>'To do','in_progress'=>'In progress','completed'=>'Completed'] as $status => $label)
    @php
        $statusTasks = $tasks->where('status', $status);
        $colors = ['todo'=>'slate','in_progress'=>'blue','completed'=>'emerald'];
        $c = $colors[$status];
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-700">{{ $label }}</h3>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-{{ $c }}-100 text-{{ $c }}-700">{{ $statusTasks->count() }}</span>
        </div>
        <div class="p-3 space-y-3 min-h-32">
            @foreach($statusTasks as $task)
            <a href="{{ route('tasks.show', $task) }}"
               class="block bg-white border border-slate-200 rounded-xl p-3 hover:border-primary-300 hover:shadow-sm transition-all">
                <p class="text-sm font-medium text-slate-900 mb-1">{{ $task->title }}</p>
                @if($task->due_date)
                <p class="text-xs {{ $task->isOverdue() ? 'text-red-500 font-medium' : 'text-slate-400' }}">
                    {{ $task->isOverdue() ? '⚠ ' : '' }}{{ $task->due_date->format('d/m/Y') }}
                </p>
                @endif
                @if($task->evaluation)
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-xs text-amber-600 font-semibold">{{ $task->evaluation->score }}/20</span>
                    <span class="text-xs text-slate-400">· {{ $task->evaluation->mention() }}</span>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

@else
{{-- Table view for supervisors & coordinator --}}
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tasks</th>
                @if(!auth()->user()->isProfessionalSupervisor())
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Created by</th>
                @endif
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mark</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Due date</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($tasks as $task)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-4 font-medium text-slate-900">{{ $task->title }}</td>
                @if(!auth()->user()->isProfessionalSupervisor())
                <td class="px-5 py-4 text-slate-600 text-xs">{{ $task->creator->username ?? '—' }}</td>
                @endif
                <td class="px-5 py-4 text-slate-700">{{ $task->student->full_name }}</td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $task->statusBadgeClass() }}">
                        {{ ['todo'=>'To do','in_progress'=>'In progress','completed'=>'Completed'][$task->status] }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    @if($task->evaluation)
                    <span class="text-sm font-bold text-amber-600">{{ $task->evaluation->score }}/20</span>
                    @else
                    <span class="text-xs text-slate-400">Not evaluated</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-slate-600' }}">
                    {{ $task->due_date ? $task->due_date->format('d/m/Y') : '—' }}
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="text-primary-600 hover:text-primary-800 font-medium text-xs">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endif
@endsection