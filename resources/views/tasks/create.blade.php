@extends('layouts.app')
@section('title', 'New task')
@section('page-title', 'Create a task')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">

        @if($placements->isEmpty())
        <div class="text-center py-8">
            <div class="text-4xl mb-3">📋</div>
            <p class="text-slate-500 text-sm">No approved placement is associated to you for the moment.</p>
            <p class="text-slate-400 text-xs mt-1">Contact the coordinator to assign you a student.</p>
        </div>
        @else
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-5">
            @csrf

            {{-- Placement selector — drives student automatically --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Placement concerned *
                    <span class="text-xs font-normal text-slate-400 ml-1">(select a placement, student fills automatically)</span>
                </label>
                <select name="placement_id" id="placement_select" required
                    onchange="syncStudent(this)"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">-- Select placement --</option>
                    @foreach($placements as $placement)
                    <option
                        value="{{ $placement->id }}"
                        data-student-id="{{ $placement->student_id }}"
                        data-student-name="{{ $placement->student->full_name }}"
                        {{ old('placement_id') == $placement->id ? 'selected' : '' }}>
                        {{ $placement->student->full_name }} — {{ $placement->company_name }}
                        ({{ $placement->start_date->format('d/m/Y') }} → {{ $placement->end_date->format('d/m/Y') }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Hidden student_id — filled automatically when placement is selected --}}
            <input type="hidden" name="student_id" id="student_id_input" value="{{ old('student_id') }}">

            {{-- Student display (read-only feedback) --}}
            <div id="student_display" class="{{ old('placement_id') ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Student assigned</label>
                <div class="flex items-center gap-3 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center
                                text-primary-700 font-bold text-xs" id="student_avatar">?</div>
                    <span class="text-sm font-medium text-slate-800" id="student_name_display">
                        @if(old('placement_id'))
                            @php $sel = $placements->firstWhere('id', old('placement_id')); @endphp
                            {{ $sel?->student->full_name ?? '' }}
                        @endif
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Task title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    placeholder="ex: Deploy a connection module"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                    placeholder="clearly describe the task, awaited deliverables...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Due date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold
                           px-6 py-2.5 rounded-xl transition-colors">
                    Create task
                </button>
                <a href="{{ route('tasks.index') }}"
                   class="text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5">
                    Cancel
                </a>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
function syncStudent(select) {
    const opt    = select.options[select.selectedIndex];
    const sid    = opt.dataset.studentId   || '';
    const sname  = opt.dataset.studentName || '';
    const display = document.getElementById('student_display');
    const input   = document.getElementById('student_id_input');
    const nameEl  = document.getElementById('student_name_display');
    const avatar  = document.getElementById('student_avatar');

    input.value   = sid;
    nameEl.textContent = sname;
    avatar.textContent = sname ? sname.charAt(0).toUpperCase() : '?';
    display.classList.toggle('hidden', !sid);
}

// Init on page load if old() value restored
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('placement_select');
    if (sel && sel.value) syncStudent(sel);
});
</script>
@endsection