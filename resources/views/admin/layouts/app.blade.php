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
        <div class="relative flex flex-1 flex-col h-screen overflow-hidden min-w-0">
            <!-- Mobile Search Backdrop (sits below header, above main content) -->
            <div id="adminNavSearchBackdrop" class="absolute inset-0 z-[55] hidden bg-black/60 backdrop-blur-[2px] sm:hidden"></div>

            <!-- Top Navigation Bar (Fixed at top of content area) -->
            <header class="relative z-[60] flex h-20 shrink-0 items-center justify-between gap-3 border-b border-white/10 bg-[#0B0F17]/80 px-4 sm:px-6 backdrop-blur-md">
                <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                    <button type="button" class="lg:hidden shrink-0 text-slate-400 hover:text-white" onclick="document.getElementById('adminSidebar').classList.toggle('-translate-x-full')">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="truncate font-display text-lg sm:text-xl font-bold tracking-tight text-white select-none">@yield('page_title', 'Dashboard')</h1>
                </div>

                <!-- Header Live Search & Actions -->
                <div class="flex shrink-0 items-center gap-2.5 sm:gap-3">
                    <!-- Unified Live Search Wrapper (Inline on Desktop/Tablet, Floating Header Overlay on Mobile) -->
                    <div id="adminNavSearchWrapper" class="hidden sm:relative sm:block sm:w-64 md:w-80 lg:w-96">
                        <div class="relative flex items-center">
                            <span class="pointer-events-none absolute left-3.5 flex items-center text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input
                                type="text"
                                id="adminGlobalSearchInput"
                                placeholder="Cari menu atau pengaturan..."
                                autocomplete="off"
                                aria-label="Cari menu atau pengaturan admin"
                                aria-expanded="false"
                                aria-controls="adminGlobalSearchResults"
                                class="h-10 w-full rounded-xl border border-white/10 bg-[#0d1322] pl-10 pr-20 text-xs text-slate-100 placeholder:text-slate-500 shadow-lg transition-all focus:border-indigo-500 focus:bg-[#0d1322] focus:ring-2 focus:ring-indigo-500/25 focus:outline-none"
                            >
                            <div class="absolute right-2.5 flex items-center gap-1.5">
                                <button
                                    type="button"
                                    id="adminGlobalSearchClear"
                                    class="hidden rounded-md px-1.5 py-0.5 text-[11px] font-medium text-slate-400 hover:bg-white/10 hover:text-white cursor-pointer"
                                    title="Bersihkan pencarian"
                                >✕</button>
                                <span class="hidden sm:inline-flex items-center">
                                    <kbd id="adminGlobalSearchKbd" class="inline-flex items-center rounded-md border border-white/10 bg-white/[0.04] px-1.5 py-0.5 font-mono text-[10px] text-slate-400 select-none">Ctrl K</kbd>
                                </span>
                                <button
                                    type="button"
                                    id="adminMobileSearchClose"
                                    class="sm:hidden rounded-md px-1.5 py-0.5 text-[11px] font-semibold text-slate-300 hover:bg-white/10 hover:text-white cursor-pointer"
                                >Tutup</button>
                            </div>
                        </div>

                        <!-- Live Search Dropdown Panel -->
                        <div
                            id="adminGlobalSearchResults"
                            role="listbox"
                            class="hidden absolute right-0 left-0 top-[calc(100%+0.55rem)] z-50 overflow-hidden rounded-2xl border border-white/15 bg-[#0d1322]/95 shadow-[0_24px_70px_rgba(0,0,0,0.85)] backdrop-blur-2xl"
                        >
                            <div class="flex items-center justify-between border-b border-white/10 bg-white/[0.02] px-3.5 py-2 text-[10px] font-mono text-slate-400">
                                <span id="adminGlobalSearchCount">Menu & Pengaturan</span>
                                <span class="hidden sm:inline text-slate-500">↑↓ pilih • Enter buka • Esc tutup</span>
                            </div>
                            <div id="adminGlobalSearchList" class="max-h-[min(65vh,420px)] overflow-y-auto p-1.5 space-y-1"></div>
                        </div>
                    </div>

                    <!-- Mobile Search Toggle Button -->
                    <button
                        type="button"
                        id="adminMobileSearchBtn"
                        class="sm:hidden inline-flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-300 transition-colors hover:bg-white/10 hover:text-white cursor-pointer"
                        title="Cari menu atau pengaturan"
                        aria-label="Cari menu atau pengaturan"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>

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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const highlightSectionByHash = (hash) => {
                if (!hash || hash.length < 2) return;
                const target = document.getElementById(hash.slice(1));
                if (!target) return;
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                target.classList.add('ring-2', 'ring-indigo-500/75', 'transition-shadow', 'duration-500');
                setTimeout(() => {
                    target.classList.remove('ring-2', 'ring-indigo-500/75');
                }, 2200);
            };

            if (window.location.hash) {
                setTimeout(() => highlightSectionByHash(window.location.hash), 80);
            }
            window.addEventListener('hashchange', () => highlightSectionByHash(window.location.hash));

            const wrapper = document.getElementById('adminNavSearchWrapper');
            const backdrop = document.getElementById('adminNavSearchBackdrop');
            const input = document.getElementById('adminGlobalSearchInput');
            const clearBtn = document.getElementById('adminGlobalSearchClear');
            const kbdHint = document.getElementById('adminGlobalSearchKbd');
            const mobileOpenBtn = document.getElementById('adminMobileSearchBtn');
            const mobileCloseBtn = document.getElementById('adminMobileSearchClose');
            const resultsPanel = document.getElementById('adminGlobalSearchResults');
            const resultsList = document.getElementById('adminGlobalSearchList');
            const resultsCount = document.getElementById('adminGlobalSearchCount');

            if (!wrapper || !input || !resultsPanel || !resultsList) return;

            const iconSvg = {
                overview: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                projects: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>',
                experiences: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>',
                credentials: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>',
                messages: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                settings: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3" stroke-width="2"/><circle cx="6" cy="12" r="3" stroke-width="2"/><circle cx="18" cy="19" r="3" stroke-width="2"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke-width="2"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke-width="2"/></svg>',
                account: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
                security: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                plus: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>',
                external: '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>',
            };

            const unreadCount = {{ (int) ($unreadCount ?? 0) }};

            const searchCatalog = [
                {
                    group: 'Menu & Konten',
                    title: 'Overview Dashboard',
                    subtitle: 'Ringkasan statistik & pintasan utama panel admin',
                    url: @json(route('dashboard')),
                    badge: 'Dashboard',
                    icon: 'overview',
                    tone: 'bg-indigo-500/15 text-indigo-300 border-indigo-500/25',
                    keywords: 'overview dashboard beranda home ringkasan statistik',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Daftar Projek',
                    subtitle: 'Kelola seluruh projek, thumbnail, slug, & tech tags',
                    url: @json(route('admin.projects.index')),
                    badge: 'Projek',
                    icon: 'projects',
                    tone: 'bg-indigo-500/15 text-indigo-300 border-indigo-500/25',
                    keywords: 'projek project karya portfolio daftar tabel urut',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Tambah Projek Baru',
                    subtitle: 'Buat dan publikasikan karya projek baru',
                    url: @json(route('admin.projects.create')),
                    badge: 'Buat Baru',
                    icon: 'plus',
                    tone: 'bg-cyan-500/15 text-cyan-300 border-cyan-500/25',
                    keywords: 'tambah buat projek project baru create add',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Projek Featured',
                    subtitle: 'Filter projek unggulan di halaman utama',
                    url: @json(route('admin.projects.index', ['featured' => '1'])),
                    badge: 'Filter',
                    icon: 'projects',
                    tone: 'bg-indigo-500/15 text-indigo-300 border-indigo-500/25',
                    keywords: 'projek featured unggulan utama',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Edukasi & Pengalaman',
                    subtitle: 'Kelola seluruh riwayat karir kerja & pendidikan',
                    url: @json(route('admin.experiences.index')),
                    badge: 'Riwayat',
                    icon: 'experiences',
                    tone: 'bg-emerald-500/15 text-emerald-300 border-emerald-500/25',
                    keywords: 'edukasi pengalaman karir riwayat timeline',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Pengalaman Kerja',
                    subtitle: 'Filter daftar riwayat pengalaman kerja & posisi',
                    url: @json(route('admin.experiences.index', ['type' => 'experience'])),
                    badge: 'Filter',
                    icon: 'experiences',
                    tone: 'bg-emerald-500/15 text-emerald-300 border-emerald-500/25',
                    keywords: 'pengalaman kerja experience magang perusahaan',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Riwayat Pendidikan',
                    subtitle: 'Filter daftar sekolah, SMK, atau universitas',
                    url: @json(route('admin.experiences.index', ['type' => 'education'])),
                    badge: 'Filter',
                    icon: 'experiences',
                    tone: 'bg-cyan-500/15 text-cyan-300 border-cyan-500/25',
                    keywords: 'pendidikan edukasi sekolah universitas smk kuliah jurusan',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Tambah Edukasi / Pengalaman',
                    subtitle: 'Tambahkan riwayat pendidikan atau kerja baru',
                    url: @json(route('admin.experiences.create')),
                    badge: 'Buat Baru',
                    icon: 'plus',
                    tone: 'bg-emerald-500/15 text-emerald-300 border-emerald-500/25',
                    keywords: 'tambah buat riwayat edukasi pengalaman baru',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Sertifikat & Kredensial',
                    subtitle: 'Kelola sertifikat, prestasi, dan tech stack',
                    url: @json(route('admin.credentials.index')),
                    badge: 'Kredensial',
                    icon: 'credentials',
                    tone: 'bg-amber-500/15 text-amber-300 border-amber-500/25',
                    keywords: 'sertifikat kredensial prestasi tech stack skill',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Daftar Sertifikat',
                    subtitle: 'Kelola sertifikat keahlian & lisensi',
                    url: @json(route('admin.credentials.index', ['category' => 'certificate'])),
                    badge: 'Filter',
                    icon: 'credentials',
                    tone: 'bg-blue-500/15 text-blue-300 border-blue-500/25',
                    keywords: 'sertifikat certificate lisensi kompetensi',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Daftar Prestasi',
                    subtitle: 'Kelola penghargaan & pencapaian awards',
                    url: @json(route('admin.credentials.index', ['category' => 'achievement'])),
                    badge: 'Filter',
                    icon: 'credentials',
                    tone: 'bg-amber-500/15 text-amber-300 border-amber-500/25',
                    keywords: 'prestasi penghargaan juara achievement awards',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Tech Stack & Skill',
                    subtitle: 'Kelola ikon teknologi, framework, & bahasa',
                    url: @json(route('admin.credentials.index', ['category' => 'tech_stack'])),
                    badge: 'Filter',
                    icon: 'credentials',
                    tone: 'bg-purple-500/15 text-purple-300 border-purple-500/25',
                    keywords: 'tech stack skill teknologi framework bahasa ikon',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Tambah Kredensial / Skill',
                    subtitle: 'Tambahkan sertifikat, prestasi, atau tech stack baru',
                    url: @json(route('admin.credentials.create')),
                    badge: 'Buat Baru',
                    icon: 'plus',
                    tone: 'bg-amber-500/15 text-amber-300 border-amber-500/25',
                    keywords: 'tambah buat sertifikat prestasi tech stack skill baru',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Pesan Masuk (Inbox)',
                    subtitle: 'Baca & kelola pesan pengunjung dari Hubungi Saya',
                    url: @json(route('admin.messages.index')),
                    badge: unreadCount > 0 ? `${unreadCount} Baru` : 'Inbox',
                    icon: 'messages',
                    tone: 'bg-rose-500/15 text-rose-300 border-rose-500/25',
                    keywords: 'pesan masuk inbox kontak formulir hubungi saya email',
                },
                {
                    group: 'Menu & Konten',
                    title: 'Pesan Belum Dibaca',
                    subtitle: 'Filter pesan masuk yang berstatus belum dibaca',
                    url: @json(route('admin.messages.index', ['filter' => 'unread'])),
                    badge: 'Filter',
                    icon: 'messages',
                    tone: 'bg-amber-500/15 text-amber-300 border-amber-500/25',
                    keywords: 'pesan belum dibaca unread inbox baru',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Kontak & Medsos',
                    subtitle: 'Semua pengaturan informasi kontak & tautan sosial media',
                    url: @json(route('admin.settings.index')),
                    badge: 'Pengaturan',
                    icon: 'settings',
                    tone: 'bg-indigo-500/15 text-indigo-300 border-indigo-500/25',
                    keywords: 'pengaturan kontak medsos media sosial settings',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Email Publik, WhatsApp & Lokasi',
                    subtitle: 'Ubah email publik, telepon, link WA, domisili, & bio',
                    url: @json(route('admin.settings.index')) + '#kontak-lokasi',
                    badge: 'Bagian',
                    icon: 'settings',
                    tone: 'bg-indigo-500/15 text-indigo-300 border-indigo-500/25',
                    keywords: 'email publik telepon nomor whatsapp wa link lokasi domisili alamat bio headline',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Tautan Media Sosial (GitHub, LinkedIn, IG, TikTok)',
                    subtitle: 'Atur URL profil GitHub, LinkedIn, Instagram, & TikTok',
                    url: @json(route('admin.settings.index')) + '#media-sosial',
                    badge: 'Bagian',
                    icon: 'settings',
                    tone: 'bg-purple-500/15 text-purple-300 border-purple-500/25',
                    keywords: 'github linkedin instagram tiktok medsos tautan sosial media link url',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Pengaturan Akun & Keamanan',
                    subtitle: 'Kelola profil admin, password, dan URL login khusus',
                    url: @json(route('admin.account.index')),
                    badge: 'Akun',
                    icon: 'account',
                    tone: 'bg-cyan-500/15 text-cyan-300 border-cyan-500/25',
                    keywords: 'pengaturan akun admin keamanan profile account security',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Ubah Nama & Email Login Admin',
                    subtitle: 'Perbarui nama akun dan email masuk panel admin',
                    url: @json(route('admin.account.index')) + '#profil-akun',
                    badge: 'Bagian',
                    icon: 'account',
                    tone: 'bg-indigo-500/15 text-indigo-300 border-indigo-500/25',
                    keywords: 'ubah nama username profil email login masuk admin',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Ganti Password Akun',
                    subtitle: 'Perbarui kata sandi login administrator',
                    url: @json(route('admin.account.index')) + '#ganti-password',
                    badge: 'Keamanan',
                    icon: 'security',
                    tone: 'bg-emerald-500/15 text-emerald-300 border-emerald-500/25',
                    keywords: 'ganti ubah password kata sandi keamanan security',
                },
                {
                    group: 'Pengaturan & Akun',
                    title: 'Kustomisasi URL Login Admin (Slug)',
                    subtitle: 'Atur slug URL akses halaman login administrator',
                    url: @json(route('admin.account.index')) + '#url-login',
                    badge: 'Keamanan',
                    icon: 'security',
                    tone: 'bg-cyan-500/15 text-cyan-300 border-cyan-500/25',
                    keywords: 'kustomisasi custom url login slug alamat portal admin rahasia',
                },
                {
                    group: 'Tindakan Cepat',
                    title: 'Lihat Website Portofolio',
                    subtitle: 'Buka tampilan publik website di tab baru',
                    url: @json(url('/')),
                    external: true,
                    badge: 'Tab Baru ↗',
                    icon: 'external',
                    tone: 'bg-emerald-500/15 text-emerald-300 border-emerald-500/25',
                    keywords: 'lihat website portofolio situs publik halaman utama home',
                },
            ];

            let filteredItems = [...searchCatalog];
            let activeIndex = -1;
            let isOpen = false;
            let isMobileOpen = false;

            const appendHighlightedText = (parent, text, tokens) => {
                if (!tokens.length) {
                    parent.textContent = text;
                    return;
                }
                const escaped = tokens.map((t) => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).filter(Boolean);
                if (!escaped.length) {
                    parent.textContent = text;
                    return;
                }
                const regex = new RegExp(`(${escaped.join('|')})`, 'gi');
                const parts = text.split(regex);
                parts.forEach((part, idx) => {
                    if (!part) return;
                    if (idx % 2 === 1) {
                        const mark = document.createElement('mark');
                        mark.className = 'rounded bg-indigo-500/35 px-0.5 text-indigo-100';
                        mark.textContent = part;
                        parent.appendChild(mark);
                    } else {
                        parent.appendChild(document.createTextNode(part));
                    }
                });
            };

            const scoreItem = (item, rawQuery, tokens) => {
                if (!tokens.length) return 1;
                const haystack = `${item.title} ${item.subtitle} ${item.group} ${item.keywords}`.toLowerCase();
                for (const token of tokens) {
                    if (!haystack.includes(token)) return 0;
                }
                const lowerTitle = item.title.toLowerCase();
                if (lowerTitle.startsWith(rawQuery)) return 100;
                if (lowerTitle.includes(rawQuery)) return 60;
                if (tokens.every((t) => lowerTitle.includes(t))) return 40;
                return 20;
            };

            const renderResults = () => {
                const rawQuery = input.value.trim().toLowerCase();
                const tokens = rawQuery.split(/\s+/).filter(Boolean);

                if (clearBtn) {
                    clearBtn.classList.toggle('hidden', rawQuery.length === 0);
                }
                if (kbdHint) {
                    kbdHint.classList.toggle('hidden', rawQuery.length > 0);
                }

                filteredItems = searchCatalog
                    .map((item, idx) => ({ item, idx, score: scoreItem(item, rawQuery, tokens) }))
                    .filter((entry) => entry.score > 0)
                    .sort((a, b) => (b.score - a.score) || (a.idx - b.idx))
                    .map((entry) => entry.item);

                resultsList.replaceChildren();

                if (resultsCount) {
                    resultsCount.textContent = tokens.length
                        ? `${filteredItems.length} hasil ditemukan`
                        : 'Akses Cepat Menu & Pengaturan';
                }

                if (filteredItems.length === 0) {
                    activeIndex = -1;
                    const empty = document.createElement('div');
                    empty.className = 'px-4 py-8 text-center text-xs text-slate-400';
                    empty.textContent = 'Menu atau pengaturan tidak ditemukan.';
                    resultsList.appendChild(empty);
                    return;
                }

                if (activeIndex >= filteredItems.length || activeIndex < 0) {
                    activeIndex = tokens.length > 0 ? 0 : -1;
                }

                let currentGroup = '';
                filteredItems.forEach((item, index) => {
                    if (item.group !== currentGroup) {
                        currentGroup = item.group;
                        const groupLabel = document.createElement('div');
                        groupLabel.className = 'px-2.5 pt-2 pb-1 font-mono text-[10px] font-bold tracking-wider uppercase text-slate-500';
                        groupLabel.textContent = currentGroup;
                        resultsList.appendChild(groupLabel);
                    }

                    const link = document.createElement('a');
                    link.href = item.url;
                    if (item.external) {
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                    }
                    link.setAttribute('role', 'option');
                    link.dataset.index = String(index);
                    const isActive = index === activeIndex;
                    link.className = `flex items-center justify-between gap-3 rounded-xl px-2.5 py-2 text-left transition-colors cursor-pointer ${
                        isActive ? 'bg-indigo-600/25 border border-indigo-500/35' : 'border border-transparent hover:bg-white/[0.06]'
                    }`;

                    const left = document.createElement('div');
                    left.className = 'flex min-w-0 items-center gap-3';

                    const iconWrap = document.createElement('div');
                    iconWrap.className = `flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border ${item.tone}`;
                    iconWrap.innerHTML = iconSvg[item.icon] || iconSvg.overview;

                    const textWrap = document.createElement('div');
                    textWrap.className = 'min-w-0';

                    const titleEl = document.createElement('div');
                    titleEl.className = 'truncate text-xs font-semibold text-white';
                    appendHighlightedText(titleEl, item.title, tokens);

                    const subEl = document.createElement('div');
                    subEl.className = 'truncate text-[11px] text-slate-400';
                    appendHighlightedText(subEl, item.subtitle, tokens);

                    textWrap.append(titleEl, subEl);
                    left.append(iconWrap, textWrap);

                    const badge = document.createElement('span');
                    badge.className = 'shrink-0 rounded-md border border-white/10 bg-white/[0.04] px-2 py-0.5 font-mono text-[10px] text-slate-300';
                    badge.textContent = item.badge;

                    link.append(left, badge);

                    link.addEventListener('mouseenter', () => {
                        activeIndex = index;
                        updateActiveStyles();
                    });

                    link.addEventListener('click', (e) => {
                        if (!item.external) {
                            const targetUrl = new URL(item.url, window.location.origin);
                            if (targetUrl.pathname === window.location.pathname && targetUrl.hash) {
                                e.preventDefault();
                                closeDropdown();
                                history.replaceState(null, '', targetUrl.pathname + targetUrl.hash);
                                highlightSectionByHash(targetUrl.hash);
                                return;
                            }
                        }
                        closeDropdown();
                    });

                    resultsList.appendChild(link);
                });
            };

            const updateActiveStyles = () => {
                const options = resultsList.querySelectorAll('[role="option"]');
                options.forEach((opt) => {
                    const idx = Number(opt.dataset.index);
                    const active = idx === activeIndex;
                    opt.classList.toggle('bg-indigo-600/25', active);
                    opt.classList.toggle('border-indigo-500/35', active);
                    opt.classList.toggle('border-transparent', !active);
                    opt.classList.toggle('hover:bg-white/[0.06]', !active);
                    if (active) {
                        opt.scrollIntoView({ block: 'nearest' });
                    }
                });
            };

            const openDropdown = () => {
                isOpen = true;
                resultsPanel.classList.remove('hidden');
                input.setAttribute('aria-expanded', 'true');
                renderResults();
            };

            const closeDropdown = () => {
                isOpen = false;
                activeIndex = -1;
                resultsPanel.classList.add('hidden');
                input.setAttribute('aria-expanded', 'false');

                if (isMobileOpen) {
                    isMobileOpen = false;
                    wrapper.classList.add('hidden');
                    wrapper.classList.remove('block', 'max-sm:absolute', 'max-sm:inset-x-3', 'max-sm:top-4', 'max-sm:z-[60]');
                    if (backdrop) backdrop.classList.add('hidden');
                }
            };

            const openMobileSearch = () => {
                isMobileOpen = true;
                document.getElementById('adminSidebar')?.classList.add('-translate-x-full');
                wrapper.classList.remove('hidden');
                wrapper.classList.add('block', 'max-sm:absolute', 'max-sm:inset-x-3', 'max-sm:top-4', 'max-sm:z-[60]');
                if (backdrop) backdrop.classList.remove('hidden');
                openDropdown();
                setTimeout(() => input.focus(), 20);
            };

            input.addEventListener('focus', openDropdown);
            input.addEventListener('click', () => {
                if (!isOpen) openDropdown();
            });
            input.addEventListener('input', () => {
                if (!isOpen) openDropdown();
                else renderResults();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!isOpen) openDropdown();
                    if (filteredItems.length > 0) {
                        activeIndex = (activeIndex + 1) % filteredItems.length;
                        updateActiveStyles();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (filteredItems.length > 0) {
                        activeIndex = activeIndex <= 0 ? filteredItems.length - 1 : activeIndex - 1;
                        updateActiveStyles();
                    }
                } else if (e.key === 'Enter') {
                    if (isOpen && activeIndex >= 0 && filteredItems[activeIndex]) {
                        e.preventDefault();
                        const chosen = resultsList.querySelector(`[role="option"][data-index="${activeIndex}"]`);
                        if (chosen) chosen.click();
                    }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closeDropdown();
                    input.blur();
                }
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    input.value = '';
                    renderResults();
                    input.focus();
                });
            }

            if (mobileOpenBtn) {
                mobileOpenBtn.addEventListener('click', openMobileSearch);
            }
            if (mobileCloseBtn) {
                mobileCloseBtn.addEventListener('click', closeDropdown);
            }
            if (backdrop) {
                backdrop.addEventListener('click', closeDropdown);
            }

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target) && (!mobileOpenBtn || !mobileOpenBtn.contains(e.target))) {
                    closeDropdown();
                }
            });

            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    if (window.innerWidth < 640 && !isMobileOpen) {
                        openMobileSearch();
                    } else {
                        input.focus();
                        input.select();
                        openDropdown();
                    }
                    return;
                }

                if (e.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName) && !document.activeElement?.isContentEditable) {
                    e.preventDefault();
                    if (window.innerWidth < 640 && !isMobileOpen) {
                        openMobileSearch();
                    } else {
                        input.focus();
                        openDropdown();
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

