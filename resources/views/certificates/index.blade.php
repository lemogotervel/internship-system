@extends('layouts.app')
@section('title', 'Certificates')
@section('page-title', 'End of internship Certificate')

@section('header-actions')
@if(auth()->user()->isProfessionalSupervisor())
<a href="{{ route('certificates.create') }}"
   class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
    Upload a Certificate
</a>
@endif
@endsection

@section('content')

@if($certificates->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="text-5xl mb-3">📜</div>
    <p class="text-slate-500 text-sm">No certificate.</p>
    @if(auth()->user()->isProfessionalSupervisor())
    <a href="{{ route('certificates.create') }}" class="mt-3 inline-block text-primary-600 text-sm font-medium hover:underline">Upload first certificate</a>
    @endif
</div>
@else
<div class="grid grid-cols-1 gap-4">
    @foreach($certificates as $cert)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div class="flex-1">
            @if(!auth()->user()->isStudent())
            <p class="text-sm font-semibold text-slate-900">{{ $cert->student->full_name }}</p>
            @endif
            <p class="text-sm text-slate-700">{{ $cert->placement->company_name }}</p>
            <p class="text-xs text-slate-400 mt-0.5">{{ $cert->original_filename ?? 'attestation.pdf' }} · Upload the cetificate{{ $cert->created_at->format('d/m/Y') }}</p>
        </div>
        <a href="{{ Storage::url($cert->file_path) }}" target="_blank"
           class="shrink-0 inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download
        </a>
    </div>
    @endforeach
</div>
@endif
@endsection