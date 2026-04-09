@extends('layouts.app')
@section('title', 'Upload a Certificate')
@section('page-title', 'Upload a Certificate')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">

        @if($placements->isEmpty())
        <div class="text-center py-8">
            <p class="text-slate-500 text-sm">No approved placement associates you</p>
        </div>
        @else
        <form method="POST" action="{{ route('certificates.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">placement concerned *</label>
                <select name="placement_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Select a placement --</option>
                    @foreach($placements as $p)
                    <option value="{{ $p->id }}" {{ old('placement_id')==$p->id ? 'selected' : '' }}>
                        {{ $p->student->full_name }} — {{ $p->company_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5"> PDF file *</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-primary-400 transition-colors">
                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <p class="text-sm text-slate-500 mb-2">Submitt or click on choose</p>
                    <input type="file" name="file" accept=".pdf" required
                        class="text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <p class="text-xs text-slate-400 mt-2">PDF only — max 5 Mb</p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Upload Certificate
                </button>
                <a href="{{ route('certificates.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5">Annuler</a>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection