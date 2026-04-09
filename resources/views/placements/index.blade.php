@extends('layouts.app')
@section('title', 'Placements')
@section('page-title', 'Placements')

@section('header-actions')
@if(auth()->user()->isStudent())
<a href="{{ route('placements.create') }}"
   class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New request 
</a>
@endif
@endsection

@section('content')

@php
$statusConfig = [
    'pending'  => ['label'=>'pending', 'class'=>'bg-amber-100 text-amber-700'],
    'approved' => ['label'=>'Approuved',   'class'=>'bg-emerald-100 text-emerald-700'],
    'rejected' => ['label'=>'Rejected',     'class'=>'bg-red-100 text-red-700'],
];
@endphp

@if($placements->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
    <div class="text-5xl mb-3">🏢</div>
    <p class="text-slate-500 text-sm">No internship found.</p>
    @if(auth()->user()->isStudent())
    <a href="{{ route('placements.create') }}" class="mt-3 inline-block text-primary-600 text-sm font-medium hover:underline">Submitt first request</a>
    @endif
</div>
@else
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                @if(auth()->user()->isCoordinator())
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                @endif
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Company</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Period</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($placements as $placement)
            <tr class="hover:bg-slate-50 transition-colors">
                @if(auth()->user()->isCoordinator())
                <td class="px-5 py-4 font-medium text-slate-900">{{ $placement->student->full_name }}</td>
                @endif
                <td class="px-5 py-4">
                    <p class="font-medium text-slate-900">{{ $placement->company_name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ mb_strimwidth($placement->company_address, 0, 40, '…') }}</p>
                </td>
                <td class="px-5 py-4 text-slate-600">
                    {{ $placement->start_date->format('d/m/Y') }} → {{ $placement->end_date->format('d/m/Y') }}
                </td>
                <td class="px-5 py-4">
                    @php $s = $statusConfig[$placement->status]; @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $s['class'] }}">{{ $s['label'] }}</span>
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('placements.show', $placement) }}"
                       class="text-primary-600 hover:text-primary-800 font-medium text-xs">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection