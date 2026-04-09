@extends('layouts.app')
@section('title', 'Assign supervisor')
@section('page-title', 'Assign supervisor to student')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">
        <form method="POST" action="{{ route('supervisors.assign.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Student *</label>
                <select name="student_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Selectr --</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id')==$student->id ? 'selected' : '' }}>
                        {{ $student->full_name }} ({{ $student->student_number }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Placement appoved *</label>
                <select name="placement_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Select --</option>
                    @foreach($placements as $placement)
                    <option value="{{ $placement->id }}" {{ old('placement_id')==$placement->id ? 'selected' : '' }}>
                        {{ $placement->student->full_name }} — {{ $placement->company_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Supervisor *</label>
                <select name="supervisor_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Select --</option>
                    @foreach($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id')==$supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->full_name }} ({{ $supervisor->type === 'academic' ? 'Academic' : 'Professional' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Supervisor Type *</label>
                <select name="type" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="academic"      {{ old('type')==='academic' ? 'selected' : '' }}>Academic</option>
                    <option value="professional"  {{ old('type')==='professional' ? 'selected' : '' }}>Professional</option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Assign supervisor
                </button>
                <a href="{{ route('supervisors.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection