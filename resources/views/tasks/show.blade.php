@extends('layouts.app')
@section('title', 'Tasks')
@section('page-title', 'Task details')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Task card --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $task->title }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $task->student->full_name }} · {{ $task->placement->company_name }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $task->statusBadgeClass() }}">
                {{ ['todo'=>'To do','in_progress'=>'In progress','completed'=>'Completed'][$task->status] }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 text-sm mb-4">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500">Créée par</p>
                <p class="font-medium text-slate-800">{{ $task->creator->username }}</p>
            </div>
            @if($task->due_date)
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500">Due date</p>
                <p class="font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-slate-800' }}">
                    {{ $task->due_date->format('d/m/Y') }}
                    @if($task->isOverdue()) <span class="text-xs">⚠</span> @endif
                </p>
            </div>
            @endif
        </div>

        @if($task->description)
        <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">
            {!! nl2br(e($task->description)) !!}
        </div>
        @endif

        {{-- Student: update status --}}
        @if(auth()->user()->isStudent() && auth()->user()->student->id === $task->student_id)
        <form method="POST" action="{{ route('tasks.status', $task) }}" class="mt-4 flex items-center gap-3">
            @csrf
            <select name="status" class="px-3 py-2 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                @foreach(['todo'=>'To do','in_progress'=>'In progress','completed'=>'Completed'] as $val => $lbl)
                <option value="{{ $val }}" {{ $task->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                Update
            </button>
        </form>
        @endif
    </div>

    {{-- Evaluation card --}}
    @if($task->evaluation)
    <div class="bg-white rounded-2xl border border-amber-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Task evaluation</h3>
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="text-center bg-amber-50 rounded-xl p-4">
                <p class="text-3xl font-bold text-amber-600">{{ $task->evaluation->score }}</p>
                <p class="text-xs text-slate-500 mt-1">/ 20</p>
            </div>
            <div class="col-span-2 flex flex-col justify-center">
                <p class="text-lg font-semibold text-slate-800">{{ $task->evaluation->mention() }}</p>
                <p class="text-xs text-slate-500">Évalué par {{ $task->evaluation->evaluator->username }}</p>
                <p class="text-xs text-slate-400">{{ $task->evaluation->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        @if($task->evaluation->feedback)
        <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700">
            <p class="text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">Feedback</p>
            {!! nl2br(e($task->evaluation->feedback)) !!}
        </div>
        @endif
    </div>

    @elseif(auth()->user()->isProfessionalSupervisor() && auth()->id() === $task->created_by)
    {{-- Professional supervisor: evaluate form --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Evaluate this task</h3>
        <form method="POST" action="{{ route('tasks.evaluate', $task) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Note (sur 20) *</label>
                <div class="flex items-center gap-3">
                    <input type="number" name="score" min="0" max="20" step="0.5" required
                        class="w-28 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                        placeholder="ex: 15">
                    <span class="text-sm text-slate-500">/ 20</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Feedback</label>
                <textarea name="feedback" rows="3"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder=" comment on the student's weak and strong points..."></textarea>
            </div>
            <button type="submit"
                class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                Save Evaluation
            </button>
        </form>
    </div>

    @elseif(!$task->evaluation && !auth()->user()->isProfessionalSupervisor())
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-center">
        <p class="text-sm text-slate-500">This task have not yet been evaluated by the professional supervisor.</p>
    </div>
    @endif

</div>
@endsection