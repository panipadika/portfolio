<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') — Pani Padika Portfolio</title>

    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #0B0F17;
            color: #F8FAFC;
        }
        .font-display {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        *, *::before, *::after {
            cursor: default;
            user-select: none;
            -webkit-user-select: none;
        }
        a, button, [role="button"], input[type="button"], input[type="submit"], input[type="reset"], input[type="checkbox"], input[type="radio"], select, option, summary, label[for], .cursor-pointer {
            cursor: pointer;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="search"], input[type="number"], input[type="tel"], input[type="url"], input[type="date"], input[type="datetime-local"], input:not([type]), textarea, [contenteditable="true"], [contenteditable=""], .cursor-text {
            cursor: text;
            user-select: text;
            -webkit-user-select: text;
        }
        .selectable-text, .select-text {
            user-select: text;
            -webkit-user-select: text;
        }
        button:disabled, input:disabled, textarea:disabled, select:disabled, [aria-disabled="true"], .cursor-not-allowed {
            cursor: not-allowed;
        }
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#0B0F17] text-slate-100 antialiased selection:bg-indigo-500/30 selection:text-indigo-200">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- Sidebar (Fixed on Desktop, Off-canvas on Mobile) -->
        <aside class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 shrink-0 flex-col border-r border-white/10 bg-[#0d1322]/95 backdrop-blur-xl transition-transform duration-300 lg:static lg:h-full lg:translate-x-0 select-none cursor-default" id="adminSidebar">
            <!-- Brand Header -->
            <div class="flex h-20 shrink-0 items-center justify-between border-b border-white/10 px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-white/15 bg-black shadow-lg shadow-black/60 p-1">
                        <img src="/assets/images/admin-logo.jpg" alt="Pani Padika" class="h-full w-full object-contain">
                    </div>
                    <div>
                        <div class="font-display text-base font-bold tracking-tight text-white select-none cursor-default">Pani Padika</div>
                        <div class="font-mono text-[10px] tracking-wider uppercase text-[#7185e8] select-none cursor-default">Admin Panel</div>
                    </div>
                </a>
                <button type="button" class="lg:hidden text-slate-400 hover:text-white" onclick="document.getElementById('adminSidebar').classList.toggle('-translate-x-full')">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1.5 overflow-y-auto px-4 py-6 select-none">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Overview</span>
                </a>

                <div class="pt-4 pb-1.5 px-3 font-mono text-[11px] font-bold tracking-wider uppercase text-slate-500 select-none cursor-default">Kelola Konten</div>

                <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    <span>Projek</span>
                </a>

                <a href="{{ route('admin.experiences.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.experiences.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                    <span>Edukasi & Pengalaman</span>
                </a>

                <a href="{{ route('admin.credentials.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.credentials.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span>Sertifikat & Skill</span>
                </a>

                <div class="pt-4 pb-1.5 px-3 font-mono text-[11px] font-bold tracking-wider uppercase text-slate-500 select-none cursor-default">Komunikasi</div>

                <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.messages.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Pesan Masuk</span>
                    </div>
                    @php
                        $unreadCount = \App\Models\Message::where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="rounded-full bg-rose-500/20 px-2 py-0.5 text-xs font-bold text-rose-300 border border-rose-500/30">{{ $unreadCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="18" cy="5" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="6" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="18" cy="19" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Kontak & Medsos</span>
                </a>

                <div class="pt-4 pb-1.5 px-3 font-mono text-[11px] font-bold tracking-wider uppercase text-slate-500 select-none cursor-default">Pengaturan</div>

                <a href="{{ route('admin.account.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.account.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-inner' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Pengaturan Akun</span>
                </a>
            </nav>

            <!-- Sidebar Footer: Logout Button -->
            <div class="border-t border-white/10 p-4 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center rounded-xl border border-rose-500/20 bg-rose-500/10 px-4 py-2.5 text-xs font-semibold text-rose-300 transition-all hover:bg-rose-500/20 hover:border-rose-500/40 hover:text-rose-200 cursor-pointer shadow-sm">
                        <span class="flex items-center gap-2.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper (Takes remaining width and full height) -->
        <div class="flex flex-1 flex-col h-screen overflow-hidden min-w-0">
            <!-- Top Navigation Bar (Fixed at top of content area) -->
            <header class="flex h-20 shrink-0 items-center justify-between border-b border-white/10 bg-[#0B0F17]/80 px-6 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <button type="button" class="lg:hidden text-slate-400 hover:text-white" onclick="document.getElementById('adminSidebar').classList.toggle('-translate-x-full')">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-display text-xl font-bold tracking-tight text-white select-none">@yield('page_title', 'Dashboard')</h1>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3.5 py-2 text-xs font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white" title="Buka website portofolio">
                        <svg class="h-4 w-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span class="hidden md:inline">Lihat Website</span>
                        <span class="text-slate-500 text-[10px]">↗</span>
                    </a>
                </div>
            </header>

            <!-- Main Page Body (The ONLY element that scrolls vertically) -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                <!-- Flash Notification Messages -->
                @if(session('success'))
                    <div class="mb-6 flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-200 backdrop-blur-md">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="text-emerald-400 hover:text-emerald-200" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 flex items-center justify-between rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm font-medium text-rose-200 backdrop-blur-md">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="text-rose-400 hover:text-rose-200" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm font-medium text-rose-200 backdrop-blur-md">
                        <div class="mb-2 flex items-center gap-2 font-semibold">
                            <svg class="h-5 w-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Terdapat beberapa kesalahan input:</span>
                        </div>
                        <ul class="list-disc pl-6 space-y-1 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

