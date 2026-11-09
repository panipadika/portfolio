@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Projects Stat -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 shadow-xl backdrop-blur-xl transition-all duration-300 hover:border-indigo-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-semibold uppercase tracking-wider text-slate-400">Total Projek</span>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display text-3xl font-extrabold text-white">{{ $stats['projects_count'] }}</span>
                <span class="text-xs text-indigo-300">({{ $stats['featured_projects_count'] }} Featured)</span>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#7185e8] hover:text-white transition-colors">
                Kelola projek
            </a>
        </div>

        <!-- Experiences Stat -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 shadow-xl backdrop-blur-xl transition-all duration-300 hover:border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-semibold uppercase tracking-wider text-slate-400">Riwayat Karir</span>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display text-3xl font-extrabold text-white">{{ $stats['education_count'] + $stats['experience_count'] }}</span>
                <span class="text-xs text-emerald-300">({{ $stats['education_count'] }} Edu, {{ $stats['experience_count'] }} Exp)</span>
            </div>
            <a href="{{ route('admin.experiences.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 hover:text-white transition-colors">
                Kelola riwayat
            </a>
        </div>

        <!-- Credentials Stat -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 shadow-xl backdrop-blur-xl transition-all duration-300 hover:border-amber-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-semibold uppercase tracking-wider text-slate-400">Sertifikat & Skill</span>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display text-3xl font-extrabold text-white">{{ $stats['certificates_count'] + $stats['achievements_count'] + $stats['tech_stack_count'] }}</span>
                <span class="text-xs text-amber-300">({{ $stats['certificates_count'] }} Sertifikat, {{ $stats['tech_stack_count'] }} Tech)</span>
            </div>
            <a href="{{ route('admin.credentials.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 hover:text-white transition-colors">
                Kelola kredensial 
            </a>
        </div>

        <!-- Messages Stat -->
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 shadow-xl backdrop-blur-xl transition-all duration-300 hover:border-rose-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-semibold uppercase tracking-wider text-slate-400">Pesan Masuk</span>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display text-3xl font-extrabold text-white">{{ $stats['total_messages_count'] }}</span>
                @if($stats['unread_messages_count'] > 0)
                    <span class="rounded-full bg-rose-500/20 px-2 py-0.5 text-xs font-bold text-rose-300 border border-rose-500/30">{{ $stats['unread_messages_count'] }} belum dibaca</span>
                @else
                    <span class="text-xs text-slate-400">Semua dibaca</span>
                @endif
            </div>
            <a href="{{ route('admin.messages.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 hover:text-white transition-colors">
                Buka inbox pesan 
            </a>
        </div>
    </div>

    <!-- Quick Actions Panel (Clean, Cohesive & Compact) -->
    <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-5 shadow-xl backdrop-blur-xl">
        <div class="flex items-center justify-between mb-3.5">
            <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400">Aksi Cepat</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">
            <a href="{{ route('admin.projects.create') }}" class="group flex items-center justify-center gap-2 rounded-xl border border-indigo-500/25 bg-indigo-600/15 px-3 py-2.5 text-xs font-semibold text-indigo-200 transition-all duration-200 hover:bg-indigo-600/25 hover:border-indigo-500/40 hover:scale-[1.02] active:scale-[0.98] shadow-sm">
                <svg class="h-4 w-4 shrink-0 text-indigo-400 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="truncate">Tambah Projek</span>
            </a>
            <a href="{{ route('admin.experiences.create', ['type' => 'experience']) }}" class="group flex items-center justify-center gap-2 rounded-xl border border-emerald-500/25 bg-emerald-600/15 px-3 py-2.5 text-xs font-semibold text-emerald-200 transition-all duration-200 hover:bg-emerald-600/25 hover:border-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] shadow-sm">
                <svg class="h-4 w-4 shrink-0 text-emerald-400 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="truncate">Tambah Pengalaman</span>
            </a>
            <a href="{{ route('admin.experiences.create', ['type' => 'education']) }}" class="group flex items-center justify-center gap-2 rounded-xl border border-cyan-500/25 bg-cyan-600/15 px-3 py-2.5 text-xs font-semibold text-cyan-200 transition-all duration-200 hover:bg-cyan-600/25 hover:border-cyan-500/40 hover:scale-[1.02] active:scale-[0.98] shadow-sm">
                <svg class="h-4 w-4 shrink-0 text-cyan-400 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="truncate">Tambah Pendidikan</span>
            </a>
            <a href="{{ route('admin.credentials.create', ['category' => 'certificate']) }}" class="group flex items-center justify-center gap-2 rounded-xl border border-amber-500/25 bg-amber-600/15 px-3 py-2.5 text-xs font-semibold text-amber-200 transition-all duration-200 hover:bg-amber-600/25 hover:border-amber-500/40 hover:scale-[1.02] active:scale-[0.98] shadow-sm">
                <svg class="h-4 w-4 shrink-0 text-amber-400 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="truncate">Tambah Sertifikat</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="group flex items-center justify-center gap-2 rounded-xl border border-slate-700/60 bg-slate-800/40 px-3 py-2.5 text-xs font-semibold text-slate-300 transition-all duration-200 hover:bg-slate-800/70 hover:border-slate-600 hover:scale-[1.02] active:scale-[0.98] shadow-sm">
                <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform group-hover:scale-110 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="18" cy="5" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="6" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="18" cy="19" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="truncate">Kontak & Medsos</span>
            </a>
        </div>
    </div>

    <!-- Two Column Recent Feeds -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Recent Messages -->
        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 shadow-2xl backdrop-blur-xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h2 class="font-display text-lg font-bold text-white">Pesan Masuk Terbaru</h2>
                    <p class="text-xs text-slate-400">Pesan yang dikirim pengunjung melalui formulir kontak</p>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-[#7185e8] hover:text-white transition-colors">Lihat Semua</a>
            </div>

            <div class="mt-4 divide-y divide-white/5">
                @forelse($recentMessages as $msg)
                    <div class="py-3.5 first:pt-1 last:pb-1 flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm text-white truncate">{{ $msg->name }}</span>
                                @if(!$msg->is_read)
                                    <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse" title="Belum dibaca"></span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 font-mono">{{ $msg->email }}</p>
                            <p class="mt-1 text-xs text-slate-300 line-clamp-1">{{ Str::limit($msg->message, 80) }}</p>
                            <span class="mt-1 inline-block text-[10px] text-slate-500 font-mono">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('admin.messages.show', $msg) }}" class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-xs font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-colors shrink-0">
                            Baca
                        </a>
                    </div>
                @empty
                    <div class="py-10 text-center text-sm text-slate-400">Belum ada pesan masuk.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 shadow-2xl backdrop-blur-xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h2 class="font-display text-lg font-bold text-white">Projek Terbaru</h2>
                    <p class="text-xs text-slate-400">Daftar projek portofolio yang baru saja dibuat/diubah</p>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="text-xs font-semibold text-[#7185e8] hover:text-white transition-colors">Lihat Semua</a>
            </div>

            <div class="mt-4 divide-y divide-white/5">
                @forelse($recentProjects as $proj)
                    <div class="py-3.5 first:pt-1 last:pb-1 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-12 w-12 rounded-xl border border-white/10 bg-slate-900/80 overflow-hidden shrink-0">
                                @if($proj->image_url)
                                    <img src="{{ $proj->image_url }}" alt="{{ $proj->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-slate-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-sm text-white truncate">{{ $proj->title }}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($proj->is_featured)
                                        <span class="rounded bg-indigo-500/20 px-1.5 py-0.5 text-[10px] font-bold text-indigo-300 border border-indigo-500/30">Featured</span>
                                    @endif
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $proj->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('admin.projects.edit', $proj) }}" class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-xs font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-colors">
                                Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-sm text-slate-400">Belum ada projek.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

