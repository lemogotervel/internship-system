@extends('layouts.app')
@section('title', 'New report')
@section('page-title', 'New report')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">
        @if($placements->isEmpty())
        <div class="text-center py-8">
            <p class="text-slate-500 text-sm">you dont have an approved placement yet. You cannot submitt a report.</p>
            <a href="{{ route('placements.create') }}" class="mt-3 inline-block text-primary-600 text-sm font-medium hover:underline">Submit internship report</a>
        </div>
        @else
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Placement concerned *</label>
                <select name="placement_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Select a Placement --</option>
                    @foreach($placements as $p)
                    <option value="{{ $p->id }}" {{ old('placement_id')==$p->id ? 'selected' : '' }}>
                        {{ $p->company_name }} ({{ $p->start_date->format('d/m/Y') }} - {{ $p->end_date->format('d/m/Y') }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Report title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="ex: Internship report — Week 3"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Submission deadline *</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Report content</label>
                <textarea name="content" rows="6"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder="Describe the activities realised, the skills acquired, Difficulties encountered ...">{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Attached file (PDF, DOC — max 5 Mo)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx"
                    class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Save
                </button>
                <a href="{{ route('reports.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5">Cancel</a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection