@extends('layouts.app')
@section('title', 'evaluate a student')
@section('page-title', 'Final evaluation')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">

        <div class="mb-6 p-4 bg-slate-50 rounded-xl text-sm">
            <p class="text-slate-500 text-xs uppercase tracking-wider font-medium mb-1">Placement concerned</p>
            <p class="font-semibold text-slate-900">{{ $placement->student->full_name }}</p>
            <p class="text-slate-600">{{ $placement->company_name }}</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $placement->start_date->format('d/m/Y') }} → {{ $placement->end_date->format('d/m/Y') }}</p>
        </div>

        <form method="POST" action="{{ route('evaluations.store', $placement) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Final mark (on 20) *</label>
                <div class="flex items-center gap-3">
                    <input type="number" name="grade" min="0" max="20" step="0.5" required
                        value="{{ old('grade') }}"
                        class="w-28 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                        placeholder="0–20">
                    <span class="text-sm text-slate-500">/ 20</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Grade</label>
                <select name="mention"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Choisir --</option>
                    @foreach(['Insuffisant','Passable','Assez Bien','Bien','Très Bien'] as $m)
                    <option value="{{ $m }}" {{ old('mention')===$m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Comments</label>
                <textarea name="comments" rows="4"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder="Appréciations générales sur le stage...">{{ old('comments') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Register the evaluation
                </button>
                <a href="{{ route('evaluations.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection