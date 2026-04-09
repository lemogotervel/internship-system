@extends('layouts.app')
@section('title', 'Connection — InternshipTrack')

@section('content')
<div class="min-h-screen flex flex-col" style="background: linear-gradient(145deg, #052e16 0%, #14532d 40%, #166534 70%, #15803d 100%);">

    {{-- Decorative circles --}}
    <div style="position:fixed;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(34,197,94,0.08);pointer-events:none;"></div>
    <div style="position:fixed;bottom:-120px;left:-60px;width:400px;height:400px;border-radius:50%;background:rgba(21,128,61,0.12);pointer-events:none;"></div>

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

    {{-- Center content --}}
    <div class="flex-1 flex items-center justify-center px-4 py-8 relative z-10">
        <div class="w-full max-w-md">

            {{-- Logo + brand --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 shadow-2xl"
                     style="background:linear-gradient(135deg,#22c55e,#15803d);box-shadow:0 8px 24px rgba(21,128,61,0.5);">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight">InternshipTrack</h1>
                <p style="color:rgba(255,255,255,0.55);font-size:13px;margin-top:4px;">Chose your profile to continue</p>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden" style="box-shadow:0 24px 64px rgba(0,0,0,0.35);">

                {{-- Role tabs --}}
                @php
                $roles = [
                    'student'                 => ['label'=>'Student',        'icon'=>'👩‍🎓'],
                    'academic_supervisor'      => ['label'=>'Academic supervisor.',      'icon'=>'🎓'],
                    'professional_supervisor'  => ['label'=>'Professional supervisor',        'icon'=>'🏢'],
                    'coordinator'             => ['label'=>'Coordinator',    'icon'=>'📋'],
                ];
                $activeRole = old('role', $role ?? 'student');
                @endphp

                <div class="grid grid-cols-4" style="border-bottom:1px solid #f1f5f9;">
                    @foreach($roles as $key => $info)
                    <button type="button"
                        onclick="switchRole('{{ $key }}')"
                        id="tab-{{ $key }}"
                        class="role-tab flex flex-col items-center gap-1 py-3 px-1 text-center transition-all"
                        style="border-bottom:2px solid {{ $activeRole===$key ? '#16a34a' : 'transparent' }};
                               background:{{ $activeRole===$key ? '#f0fdf4' : 'transparent' }};
                               color:{{ $activeRole===$key ? '#15803d' : '#94a3b8' }};">
                        <span style="font-size:18px;line-height:1;">{{ $info['icon'] }}</span>
                        <span style="font-size:10px;font-weight:600;line-height:1.2;">{{ $info['label'] }}</span>
                    </button>
                    @endforeach
                </div>

                {{-- Form --}}
                <div class="p-6">

                    @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="role" id="role-input" value="{{ $activeRole }}">

                        {{-- STUDENT --}}
                        <div id="fields-student" class="{{ $activeRole!=='student' ? 'hidden' : '' }} space-y-4">
                            <div class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold"
                                 style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                                <span>👩‍🎓</span> Connect with your name + password
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">User name</label>
                                <input type="text" name="username" value="{{ old('username') }}" autocomplete="username"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                    style="focus-ring-color:#16a34a;" placeholder="Votre identifiant"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(22,163,74,0.2)';this.style.borderColor='#16a34a';"
                                    onblur="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Password</label>
                                <input type="password" name="password" autocomplete="current-password"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none"
                                    placeholder="••••••••"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(22,163,74,0.2)';this.style.borderColor='#16a34a';"
                                    onblur="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
                            </div>
                        </div>

                        {{-- SUPERVISORS --}}
                        @foreach(['academic_supervisor','professional_supervisor'] as $supRole)
                        <div id="fields-{{ $supRole }}" class="{{ $activeRole!==$supRole ? 'hidden' : '' }} space-y-4">
                            <div class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold"
                                 style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                                <span>{{ $supRole==='academic_supervisor' ? '🎓' : '🏢' }}</span>
                                Connect with email + password
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Email address</label>
                                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none"
                                    placeholder="votre@email.com"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(22,163,74,0.2)';this.style.borderColor='#16a34a';"
                                    onblur="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Password</label>
                                <input type="password" name="password" autocomplete="current-password"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none"
                                    placeholder="••••••••"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(22,163,74,0.2)';this.style.borderColor='#16a34a';"
                                    onblur="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
                            </div>
                        </div>
                        @endforeach

                        {{-- COORDINATOR --}}
                        <div id="fields-coordinator" class="{{ $activeRole!=='coordinator' ? 'hidden' : '' }} space-y-4">
                            <div class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold"
                                 style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                                <span>📋</span> Connect with name + coordinator code
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">User name</label>
                                <input type="text" name="username" value="{{ old('username') }}" autocomplete="username"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none"
                                    placeholder="Votre identifiant"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(22,163,74,0.2)';this.style.borderColor='#16a34a';"
                                    onblur="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Coordinator code</label>
                                <input type="password" name="coordinator_code"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none"
                                    placeholder="ex: COORD-2024"
                                    onfocus="this.style.boxShadow='0 0 0 3px rgba(22,163,74,0.2)';this.style.borderColor='#16a34a';"
                                    onblur="this.style.boxShadow='';this.style.borderColor='#e2e8f0';">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full text-white font-bold py-2.5 rounded-xl text-sm transition-all mt-1"
                            style="background:linear-gradient(135deg,#22c55e,#15803d);box-shadow:0 4px 14px rgba(21,128,61,0.4);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Connect
                        </button>
                    </form>

                    {{-- Register link --}}
                    <div id="register-link" class="{{ $activeRole!=='student' ? 'hidden' : '' }} mt-4 text-center">
                        <p style="font-size:13px;color:#94a3b8;">
                            No account yet?
                            <a href="{{ route('register') }}" style="color:#16a34a;font-weight:600;" 
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                               Register
                            </a>
                        </p>
                    </div>

                    <div id="no-register-msg" class="{{ $activeRole==='student' ? 'hidden' : '' }} mt-4 text-center">
                        <p style="font-size:11px;color:#cbd5e1;">Supervisor accounts are created by the administration.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const roles = ['student','academic_supervisor','professional_supervisor','coordinator'];

function switchRole(role) {
    document.getElementById('role-input').value = role;

    roles.forEach(r => {
        const tab   = document.getElementById('tab-' + r);
        const panel = document.getElementById('fields-' + r);

        if (tab) {
            tab.style.borderBottomColor = r === role ? '#16a34a' : 'transparent';
            tab.style.background        = r === role ? '#f0fdf4' : 'transparent';
            tab.style.color             = r === role ? '#15803d' : '#94a3b8';
        }
        if (panel) {
            panel.classList.toggle('hidden', r !== role);
            panel.querySelectorAll('input').forEach(i => i.disabled = r !== role);
        }
    });

    document.getElementById('register-link').classList.toggle('hidden', role !== 'student');
    document.getElementById('no-register-msg').classList.toggle('hidden', role === 'student');
}

document.addEventListener('DOMContentLoaded', () => {
    const active = document.getElementById('role-input').value || 'student';
    switchRole(active);
});
</script>
@endsection