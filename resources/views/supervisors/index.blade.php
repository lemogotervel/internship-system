@extends('layouts.app')
@section('title', 'Supervisors')
@section('page-title', 'Supervisor Management')

@section('header-actions')
<a href="{{ route('supervisors.assign') }}"
   class="inline-flex items-center gap-2 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    Assign
</a>
<a href="{{ route('supervisors.create') }}"
   class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New supervisor
</a>
@endsection

@section('content')

@if($supervisors->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="text-5xl mb-3">👤</div>
    <p class="text-slate-500 text-sm">No supervisor registered.</p>
    <a href="{{ route('supervisors.create') }}" class="mt-3 inline-block text-primary-600 text-sm font-medium hover:underline">Add a supervisor</a>
</div>
@else
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User Type</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Structure</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Id</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Telephone</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($supervisors as $supervisor)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $supervisor->type === 'academic' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ strtoupper(substr($supervisor->full_name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-slate-900">{{ $supervisor->full_name }}</span>
                    </div>
                </td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                        {{ $supervisor->type === 'academic' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ $supervisor->type === 'academic' ? 'Academic' : 'Professional' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-slate-600">
                    {{ $supervisor->company ?? $supervisor->department ?? '—' }}
                </td>
                <td class="px-5 py-4 text-slate-500 font-mono text-xs">{{ $supervisor->user->username }}</td>
                <td class="px-5 py-4 text-slate-500">{{ $supervisor->phone ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection