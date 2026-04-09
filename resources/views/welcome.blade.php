<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internshiptrack — Internship Follow-up management system</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif'] },
                    colors: {
                        primary: {
                            50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe',
                            500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8',
                            800:'#1e40af', 900:'#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hero-grid {
            background-image:
                linear-gradient(rgba(37,99,235,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,99,235,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .card-hover  { transition: transform .2s ease, box-shadow .2s ease; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
        .fade-in   { animation: fadeUp .5s ease both; }
        .fade-in-2 { animation: fadeUp .5s .12s ease both; }
        .fade-in-3 { animation: fadeUp .5s .24s ease both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans bg-white text-slate-900 antialiased">

{{-- ── NAVBAR ── --}}
<nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0
                           3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946
                           3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138
                           3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806
                           3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438
                           3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <span class="font-bold text-slate-900 text-lg tracking-tight">InternshipTrack</span>
        </div>

        {{-- Nav links --}}
        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-500">
            <a href="#features" class="hover:text-slate-900 transition-colors">Functionalities</a>
            <a href="#workflow" class="hover:text-slate-900 transition-colors">Process</a>
            <a href="#roles"    class="hover:text-slate-900 transition-colors">Roles</a>
        </div>

        {{-- Auth buttons --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}"
               class="text-sm font-medium text-slate-600 hover:text-slate-900 px-4 py-2 rounded-xl transition-colors">
                Connect
            </a>
            <a href="{{ route('register') }}"
               class="text-sm font-semibold bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition-colors shadow-sm">
                Register
            </a>
        </div>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero-grid min-h-screen flex items-center pt-16">
    <div class="max-w-6xl mx-auto px-6 py-24 w-full">


        <div class="max-w-3xl mx-auto text-center mb-20">

            <div style="display: flex; align-items: center; width: 100%; padding: 0 50px 20px 50px; margin-top: 0; background-color: #ffffff; border-bottom: 2px solid #f1f5f9; margin-bottom: 40px;">
    
    <div style="flex-shrink: 0; margin-right: 40px; padding-top: 10px;">
        <img src="{{ asset('images/iuc-logo.jpg') }}" 
             alt="Logo IUC" 
             style="width: 180px; height: auto; display: block;">
    </div>

    <div style="padding-top: 20px;">
        <h1 style="margin: 0; font-size: 1.8rem; font-weight: 900; color: #0f172a; line-height: 1.1;">
            INSTITUT UNIVERSITAIRE DE LA COTE
        </h1>
        <p style="margin: 8px 0; font-size: 1.3rem; color: #475569; font-style: italic;">
            The Pole of Excellence in Africa
        </p>
    </div>
</div>
            
           
            <div class="fade-in inline-flex items-center gap-2 bg-primary-50 border border-primary-100 text-green-700 text-xs font-semibold px-4 py-2 rounded-full mb-8">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                IUC Internship Management System
            </div>

            <h1 class="fade-in-2 text-5xl md:text-6xl font-extrabold text-slate-900 leading-tight tracking-tight mb-6">
                Manage Your Internships<br>
                <span class="text-green-600">from end to end</span>
            </h1>

            <p class="fade-in-3 text-lg text-slate-500 leading-relaxed mb-10 max-w-xl mx-auto">
               From application to completion — InternshipTrack brings students, mentors,
                and coordinators together in a single, streamlined workspace.
            </p>

            <div class="fade-in-3 flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white
                          font-semibold px-6 py-3 rounded-xl text-sm transition-colors
                          shadow-lg shadow-primary-200">
                    Create a student account
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 border border-slate-200 hover:bg-slate-50
                          text-slate-700 font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                    connect
                </a>
            </div>
        </div>

         {{-- Role cards --}}
        <div id="roles" class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
            @foreach([
                ['icon'=>'👩‍🎓','label'=>'Student',       'desc'=>'Placement, Report, task',  'bg'=>'bg-blue-50',   'border'=>'border-blue-100'],
                ['icon'=>'🏢',  'label'=>'Professional supervisor.', 'desc'=>'Tasks and evaluations',      'bg'=>'bg-orange-50', 'border'=>'border-orange-100'],
                ['icon'=>'🎓',  'label'=>'Academic supervisor.','desc'=>'Repports and notifications',     'bg'=>'bg-emerald-50','border'=>'border-emerald-100'],
                ['icon'=>'📋',  'label'=>'Coordinator',  'desc'=>'Validation and supervision',  'bg'=>'bg-purple-50', 'border'=>'border-purple-100'],
            ] as $role)
            <div class="card-hover {{ $role['bg'] }} border {{ $role['border'] }} rounded-2xl p-5 text-center">
                <div class="text-3xl mb-3">{{ $role['icon'] }}</div>
                <p class="font-semibold text-slate-800 text-sm mb-1">{{ $role['label'] }}</p>
                <p class="text-xs text-slate-500">{{ $role['desc'] }}</p>
            </div>
            @endforeach
        </div>

         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
                {{-- <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 border border-slate-200 hover:bg-slate-50
                          text-slate-700 font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                    S'inscrire (étudiant)
                </a> --}}
            </div>
 
            {{-- Role picker modal --}}
            <div id="role-modal"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
                 style="background: rgba(0,0,0,0.5);"
                 onclick="if(event.target===this) this.classList.add('hidden')">
 
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6"
                     onclick="event.stopPropagation()">
 
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-slate-900">Connect as</h3>
                        <button onclick="document.getElementById('role-modal').classList.add('hidden')"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400
                                   hover:text-slate-700 hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
 
                    {{-- <div class="space-y-2"></div> --}}

                     @foreach([
                            ['role'=>'student',                'icon'=>'👩‍🎓','label'=>'Studentt',             'desc'=>'User name + password', 'color'=>'hover:bg-blue-50 hover:border-blue-200'],
                            ['role'=>'academic_supervisor',    'icon'=>'🎓', 'label'=>'Academic supervisor',  'desc'=>'Email + password',                'color'=>'hover:bg-emerald-50 hover:border-emerald-200'],
                            ['role'=>'professional_supervisor','icon'=>'🏢', 'label'=>'professional supervisor','desc'=>'Email + password',               'color'=>'hover:bg-orange-50 hover:border-orange-200'],
                            ['role'=>'coordinator',            'icon'=>'📋', 'label'=>'Coordinator',          'desc'=>'credential +coordinator code',      'color'=>'hover:bg-purple-50 hover:border-purple-200'],
                        ] as $r)
                        <a href="{{ route('login') }}?role={{ $r['role'] }}"
                           class="flex items-center gap-4 p-3.5 border border-slate-200 rounded-xl
                                  transition-all {{ $r['color'] }} group">
                            <div class="text-2xl w-10 h-10 flex items-center justify-center
                                        bg-slate-50 rounded-xl group-hover:scale-110 transition-transform">
                                {{ $r['icon'] }}
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-semibold text-slate-900">{{ $r['label'] }}</p>
                                <p class="text-xs text-slate-400">{{ $r['desc'] }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>
 
                </div>
            </div>
        </div>

       
    </div>
</section>

{{-- ── FEATURES ── --}}
<section id="features" class="bg-slate-50 py-24 border-t border-slate-100">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-900 mb-3">All you need</h2>
            <p class="text-slate-500 text-sm">A complete workflow for all the internship actors
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'📌','title'=>'Placement & validation',
                 'desc'=>'online submission, validation by coordinator with rejection motif, Real-time status tracking.',
                 'badge'=>'bg-blue-100 text-blue-700'],
                ['icon'=>'📄','title'=>'Report management',
                 'desc'=>'Manage PDF uploads, track deadlines, and receive overdue alerts, with integrated comments and approval workflows.',
                 'badge'=>'bg-emerald-100 text-emerald-700'],
                ['icon'=>'✅','title'=>'Task & evaluation',
                 'desc'=>"Task creation and grading (/20) by professional supervisors. Accessible to academic staff and coordinators.",
                 'badge'=>'bg-amber-100 text-amber-700'],
                ['icon'=>'⭐','title'=>'final mark',
                 'desc'=>'Grade and distinction entry (Passable, Good, Very Good...) accessible to students..',
                 'badge'=>'bg-purple-100 text-purple-700'],
                ['icon'=>'📜','title'=>'Internship Certificate',
                 'desc'=>"Upload PDF by professional supervisor. Accessible at any oment by the student.",
                 'badge'=>'bg-pink-100 text-pink-700'],
                ['icon'=>'👤','title'=>'Supervisor management',
                 'desc'=>'Coordinators create accounts and assign students to their academic or professional supervisors.',
                 'badge'=>'bg-slate-200 text-slate-700'],
            ] as $f)
            <div class="card-hover bg-white rounded-2xl border border-slate-200 p-6">
                <div class="w-10 h-10 {{ $f['badge'] }} rounded-xl flex items-center justify-center text-lg mb-4">
                    {{ $f['icon'] }}
                </div>
                <h3 class="font-semibold text-slate-900 mb-2 text-sm">{{ $f['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── WORKFLOW ── --}}
<section id="workflow" class="py-24">
    <div class="max-w-3xl mx-auto px-6">

        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-slate-900 mb-3">how it works ?</h2>
            <p class="text-slate-500 text-sm">Six stages from the initial application to the final approval.</p>
        </div>

        <div class="space-y-4">
            @foreach([
                ['step'=>'01','title'=>'REgister Student',
                 'desc'=>"Students create their accounts using their student ID number, department, and level.",
                 'color'=>'bg-green-600'],
                ['step'=>'02','title'=>'Submit internship',
                 'desc'=>"Student submits internship request. Coordinator approves or rejects with feedback.",
                 'color'=>'bg-green-600'],
                ['step'=>'03','title'=>'Supervisor Assignment',
                 'desc'=>'Coordinator assigns academic and professional supervisors to approved placements.',
                 'color'=>'bg-green-600'],
                ['step'=>'04','title'=>'Task asignment',
                 'desc'=>"Professional mentors set the tasks, students provide status updates, and supervisors assign a grade out of 20.",
                 'color'=>'bg-green-600'],
                ['step'=>'05','title'=>'Report and Academic Evaluation',
                 'desc'=>"Students upload their final reports. The academic advisor then reviews, provides comments, and assigns the final mark.",
                 'color'=>'bg-green-600'],
                ['step'=>'06','title'=>'final internship certificate',
                 'desc'=>"Professional supervisor uploads the PDF certificate. The full record is archived and accessible.",
                 'color'=>'bg-green-600'],
            ] as $i => $step)
            <div class="flex items-start gap-5">
                <div class="w-10 h-10 shrink-0 {{ $step['color'] }} rounded-full flex items-center
                            justify-center text-white font-bold text-sm shadow-lg shadow-blue-200 mt-0.5">
                    {{ $step['step'] }}
                </div>
                <div class="flex-1 bg-white border border-slate-200 rounded-2xl p-5">
                    <p class="font-semibold text-slate-900 mb-1 text-sm">{{ $step['title'] }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            </div>
            @if($i < 5)
            <div class="ml-4.5 w-px h-4 bg-slate-200 ml-5"></div>
            @endif
            @endforeach
        </div>
    </div>
</section>

{{-- ── STATS BAND ── --}}
{{-- <section class="bg-primary-600 py-14">
    <div class="max-w-4xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach([
                ['value'=>'4',  'label'=>'User role'],
                ['value'=>'12', 'label'=>'Tables en base'],
                ['value'=>'21', 'label'=>'Vues Blade'],
                ['value'=>'8',  'label'=>'Contrôleurs'],
            ] as $stat)
            <div>
                <p class="text-4xl font-extrabold text-white mb-1">{{ $stat['value'] }}</p>
                <p class="text-primary-200 text-sm">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section> --}}

{{-- CTA  --}}
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-slate-900 mb-4">Ready to load ?</h2>
        <p class="text-slate-500 mb-8 text-sm">
            Create your student account in less than 1 minute<br>
            Supervisor and coordinator accounts are managed by the administration.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('register') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold
                      px-6 py-3 rounded-xl text-sm transition-colors shadow-lg shadow-primary-200">
                Create a student account
            </a>
            
        </div>
    </div>
</section>

{{-- ── FOOTER ── --}}
<footer class="border-t border-slate-100 py-8">
    <div class="max-w-6xl mx-auto px-6 flex items-center justify-between text-sm text-slate-400 flex-wrap gap-4">
        <div class="flex items-center gap-2">
            <div class="w-5 h-5 bg-green-600 rounded flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944
                           a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9
                           c0 5.591 3.824 10.29 9 11.622
                           5.176-1.332 9-6.03 9-11.622
                           0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            {{-- <span class="font-semibold text-slate-600">IntrrnshipTrack</span> --}}
        </div>
        <p>Designed and implemented by: LEMOGO TERVEL DIVINE</p>
    </div>
</footer>

</body>
</html>