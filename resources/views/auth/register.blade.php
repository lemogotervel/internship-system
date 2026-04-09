@extends('layouts.app')
@section('title', 'Registration')

@section('content')
<div class="min-h-screen  flex items-center justify-center p-4" style="background: linear-gradient(145deg, #052e16 0%, #14532d 40%, #166534 70%, #15803d 100%);">
    <div class="w-full max-w-xl">

         {{-- Back to home --}}
    <div class="px-6 pt-5 pb-2 relative z-10">
        <a href="{{ route('welcome') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition-colors"
           style="color:rgba(255,255,255,0.6);"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to home
        </a>
    </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white tracking-tight">InternshipTrack</h1>
            <p class="text-slate-300 mt-1 text-sm">Create a student accountt</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Registration</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Complete name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Matricule *</label>
                        <input type="text" name="student_number" value="{{ old('student_number') }}" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Field *</label>
                        <input type="text" name="program" value="{{ old('program') }}" required placeholder="ex: Informatique"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">level</label>
                        <select name="level" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">-- Choose --</option>
                            @foreach(['L1','L2','L3','M1','M2'] as $l)
                            <option value="{{ $l }}" {{ old('level')===$l ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">User Name *</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Telephone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Password *</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm *</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-primary font-semibold py-2.5 px-4 rounded-xl transition-colors text-sm mt-2">
                    Create an account
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-500">
                Already registered ? <a href="{{ route('login') }}" class="text-primary-600 hover:underline font-medium">Connect</a>
            </p>
        </div>
    </div>
</div>
@endsection