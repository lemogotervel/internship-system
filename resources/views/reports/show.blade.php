@extends('layouts.app')
@section('title', 'Rapport')
@section('page-title', 'Détail du rapport')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Report card --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $report->title }}</h2>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $report->student->full_name }} · {{ $report->placement->company_name }}
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($report->status==='approved') bg-emerald-100 text-emerald-700
                @elseif($report->status==='rejected') bg-red-100 text-red-700
                @elseif($report->status==='submitted') bg-blue-100 text-blue-700
                @else bg-slate-100 text-slate-600 @endif">
                {{ ['draft'=>'Brouillon','submitted'=>'Soumis','approved'=>'Approuvé','rejected'=>'Rejeté'][$report->status] }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500">Deadline</p>
                <p class="font-medium {{ $report->isLate() ? 'text-red-600' : 'text-slate-800' }}">
                    {{ $report->due_date->format('d/m/Y') }}
                    @if($report->isLate()) <span class="text-xs">⚠ Delayed</span> @endif
                </p>
            </div>
            @if($report->submitted_at)
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-500">Submitted on</p>
                <p class="font-medium text-slate-800">{{ $report->submitted_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
        </div>

        @if($report->content)
        <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">
            {!! nl2br(e($report->content)) !!}
        </div>
        @endif

        @if($report->file_path)
        <div class="mt-4 flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            <a href="{{ Storage::url($report->file_path) }}" target="_blank"
               class="text-sm text-blue-700 font-medium hover:underline">Download file</a>
        </div>
        @endif

        {{-- Student: submit button --}}
        @if(auth()->user()->isStudent() && $report->status === 'draft')
        <form method="POST" action="{{ route('reports.submit', $report) }}" class="mt-4">
            @csrf
            <button type="submit"
                class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
               Submit report
            </button>
        </form>
        @endif
    </div>

    {{-- Supervisor review panel --}}
    @if((auth()->user()->isAcademicSupervisor() || auth()->user()->isCoordinator()) && $report->status === 'submitted')
    <div class="bg-white rounded-2xl border border-amber-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Report evaluation</h3>
        <form method="POST" action="{{ route('reports.review', $report) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Comments</label>
                <textarea name="comment" rows="3"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder="Vos remarques sur le rapport..."></textarea>
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

    {{-- Comments --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Comments ({{ $report->comments->count() }})</h3>

        @if($report->comments->isEmpty())
        <p class="text-sm text-slate-400">No comment for the moment.</p>
        @else
        <div class="space-y-4 mb-5">
            @foreach($report->comments as $comment)
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold text-xs shrink-0">
                    {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                </div>
                <div class="flex-1 bg-slate-50 rounded-xl p-3">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold text-slate-800">{{ $comment->user->username }}</span>
                        <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-slate-700">{{ $comment->comment }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Add comment --}}
        @if(!auth()->user()->isStudent() || $report->status !== 'draft')
        <form method="POST" action="{{ route('reports.comment', $report) }}" class="flex gap-3">
            @csrf
            <input type="text" name="comment" required placeholder="Add a comment..."
                class="flex-1 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <button type="submit"
                class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                Send
            </button>
        </form>
        @endif
    </div>
</div>
@endsection