<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InternshipTrack')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Per-role sidebar themes */
        .sidebar-student { background: linear-gradient(170deg,#1e3a5f 0%,#1e40af 100%); }
        .sidebar-academic_supervisor { background: linear-gradient(170deg,#064e3b 0%,#065f46 100%); }
        .sidebar-professional_supervisor { background: linear-gradient(170deg,#78350f 0%,#92400e 100%); }
        .sidebar-coordinator { background: linear-gradient(170deg,#3b0764 0%,#4c1d95 100%); }

        /* Shared sidebar pieces */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            transition: background .15s, color .15s;
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-link:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.2); color: #fff; font-weight: 600; }
        .nav-link svg { width: 16px; height: 16px; flex-shrink: 0; }

        .sidebar-divider { border-color: rgba(255,255,255,0.1); }
        .sidebar-label { color: rgba(255,255,255,0.35); font-size: 9px; text-transform: uppercase; letter-spacing: .1em; font-weight: 700; }
        .sidebar-username { color: #fff; font-weight: 700; font-size: 13px; }
        .sidebar-subrole { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 999px; border: 1px solid; display: inline-block; margin-top: 4px; }

        .sidebar-student    .sidebar-subrole { background: rgba(96,165,250,.2); color: #93c5fd; border-color: rgba(96,165,250,.3); }
        .sidebar-academic_supervisor .sidebar-subrole { background: rgba(52,211,153,.2); color: #6ee7b7; border-color: rgba(52,211,153,.3); }
        .sidebar-professional_supervisor .sidebar-subrole { background: rgba(251,191,36,.2); color: #fcd34d; border-color: rgba(251,191,36,.3); }
        .sidebar-coordinator .sidebar-subrole { background: rgba(167,139,250,.2); color: #c4b5fd; border-color: rgba(167,139,250,.3); }

        .topbar-btn {
            display:inline-flex; align-items:center; justify-content:center;
            width:30px; height:30px; border-radius:7px; color:#94a3b8;
            transition:background .15s, color .15s;
        }
        .topbar-btn:hover { background:#f1f5f9; color:#1e293b; }
    </style>
</head>
<body class="h-full bg-slate-100" style="min-height:100vh;">

@auth
@php
    $role = auth()->user()->role;
    $username = auth()->user()->username;
    $roleLabels = [
        'student'                 => 'Student',
        'academic_supervisor'     => 'Academic supervisor',
        'professional_supervisor' => 'Professional supervisor',
        'coordinator'             => 'Coordinator',
    ];
    $roleLabel = $roleLabels[$role] ?? $role;
@endphp

<div class="flex h-full min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="sidebar-{{ $role }} w-56 fixed inset-y-0 left-0 z-20 flex flex-col shadow-2xl">

        {{-- Logo --}}
        <div class="px-4 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.1);">
            <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#22c55e,#15803d);box-shadow:0 2px 10px rgba(21,128,61,0.5);">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">InternshipTrack</p>
                <p style="color:rgba(255,255,255,0.4);font-size:10px;">Internship Follow-up Management</p>
            </div>
        </div>

        {{-- User card --}}
        <div class="mx-3 mt-3 mb-1 rounded-xl px-3 py-2.5" style="background:rgba(0,0,0,0.18);">
            <p class="sidebar-label mb-1">Connected as</p>
            <p class="sidebar-username truncate">{{ $username }}</p>
            <span class="sidebar-subrole">{{ $roleLabel }}</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 mt-2 space-y-0.5 overflow-y-auto pb-4">

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('placements.index') }}"
               class="nav-link {{ request()->routeIs('placements.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Placements
            </a>

            <a href="{{ route('reports.index') }}"
               class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Reports
            </a>

            <a href="{{ route('tasks.index') }}"
               class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Tasks
            </a>

            @if(!auth()->user()->isStudent())
            <a href="{{ route('evaluations.index') }}"
               class="nav-link {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                {{ auth()->user()->isProfessionalSupervisor() ? 'Éval. tâches' : 'Évaluations' }}
            </a>
            @endif

            @if(!auth()->user()->isStudent())
            <a href="{{ route('certificates.index') }}"
               class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Certificates
            </a>
            @endif

            @if(auth()->user()->isCoordinator())
            <div class="pt-3 mt-2" style="border-top:1px solid rgba(255,255,255,0.1);">
                <p class="sidebar-label px-3 mb-2">Administration</p>
                <a href="{{ route('supervisors.index') }}"
                   class="nav-link {{ request()->routeIs('supervisors.*') ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Supervisors
                </a>
            </div>
            @endif

        </nav>

        <div class="px-4 pb-3" style="border-top:1px solid rgba(255,255,255,0.07);padding-top:10px;">
            <p style="color:rgba(255,255,255,0.2);font-size:9px;">InternshipTrack v1.0</p>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 ml-56 min-h-screen flex flex-col">

        {{-- Top bar --}}
        <header class="bg-white border-b border-slate-200 px-5 py-2.5 sticky top-0 z-10 flex items-center justify-between shadow-sm" style="background:rgba(0, 0, 0, 0.438);">
            <div class="flex items-center gap-1.5" style="background:rgba(0, 0, 0, 0.18);">
                <button onclick="history.back()" title="Preevious" class="topbar-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="history.forward()" title="Next" class="topbar-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div class="w-px h-4 bg-slate-200 mx-1"></div>
                <a href="{{ route('welcome') }}" title="Welcome" class="topbar-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>
                <div class="w-px h-4 bg-slate-200 mx-1"></div>
                <h1 class="text-sm font-semibold text-slate-800">@yield('page-title','Dashboard')</h1>
            </div>

            <div class="flex items-center gap-2">
                @yield('header-actions')
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500
                               hover:text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors
                               border border-slate-200 hover:border-red-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Disconnect
                    </button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <div class="flex-1 px-6 py-5">
            @if(session('success'))
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <p class="font-semibold mb-1">Please correct your error</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

@else
    @yield('content')
@endauth

</body>
</html>