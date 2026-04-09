@extends('layouts.app')
@section('title', 'New Supervisor')
@section('page-title', 'Create a supervisor')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-200 p-8">
        <form method="POST" action="{{ route('supervisors.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Supervisor Type *</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="academic" {{ old('type','academic')==='academic' ? 'checked' : '' }}
                            class="text-primary-600">
                        <span class="text-sm text-slate-700">Académic (university)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="professional" {{ old('type')==='professional' ? 'checked' : '' }}
                            class="text-primary-600">
                        <span class="text-sm text-slate-700">Professional (company)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Complete name *</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Company / Department</label>
                    <input type="text" name="company" value="{{ old('company') }}" placeholder="Company name"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Telephone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <hr class="border-slate-100">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Acces to the platform</p>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Id *</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Temporary password *</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Create supervisor
                </button>
                <a href="{{ route('supervisors.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection