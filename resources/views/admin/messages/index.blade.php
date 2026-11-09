@extends('admin.layouts.app')

@section('title', 'Kotak Masuk Pesan')
@section('page_title', 'Pesan Masuk (Hubungi Saya)')

@section('content')
<div class="space-y-6">
    <!-- Top Filter Bar & Search -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Filter Tabs -->
        <div class="inline-flex rounded-2xl border border-white/10 bg-[#0d1322] p-1 shadow-lg">
            <a href="{{ route('admin.messages.index') }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ !request('filter') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400 hover:text-white' }}">
                Semua Pesan
            </a>
            <a href="{{ route('admin.messages.index', ['filter' => 'unread', 'search' => request('search')]) }}" class="relative rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ request('filter') === 'unread' ? 'bg-amber-600/20 text-amber-300 border border-amber-500/30' : 'text-slate-400 hover:text-white' }}">
                Belum Dibaca
                @if($unreadCount > 0)
                    <span class="ml-1.5 inline-flex items-center justify-center rounded-full bg-amber-500/20 px-1.5 py-0.5 text-[10px] font-bold text-amber-300">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.messages.index', ['filter' => 'read', 'search' => request('search')]) }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ request('filter') === 'read' ? 'bg-slate-700/40 text-slate-200 border border-slate-600/30' : 'text-slate-400 hover:text-white' }}">
                Sudah Dibaca
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.messages.index') }}" class="relative w-full sm:w-72">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari pengirim, email, isi pesan..."
                class="h-10 w-full rounded-2xl border border-white/10 bg-[#0d1322] pl-10 pr-4 text-xs text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
            >
            <svg class="absolute left-3.5 top-3 h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            @if(request('search'))
                <a href="{{ route('admin.messages.index', ['filter' => request('filter')]) }}" class="absolute right-3 top-2.5 text-xs text-slate-400 hover:text-white">✕</a>
            @endif
        </form>
    </div>

    <!-- Messages Table Card -->
    <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="border-b border-white/10 bg-white/[0.02] font-mono text-[11px] uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Pengirim</th>
                        <th class="px-6 py-4">Pesan</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 font-sans">
                    @forelse($messages as $msg)
                        <tr class="transition-colors hover:bg-white/[0.02] {{ !$msg->is_read ? 'bg-amber-500/[0.03]' : '' }}">
                            <td class="px-6 py-4">
                                @if(!$msg->is_read)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/20 px-2.5 py-1 text-[11px] font-bold text-amber-300 border border-amber-500/30">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Baru
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-white/5 px-2.5 py-1 text-[11px] font-medium text-slate-400 border border-white/5">
                                        Dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-sm {{ !$msg->is_read ? 'text-white' : 'text-slate-200' }}">{{ $msg->name }}</div>
                                <div class="font-mono text-[11px] text-slate-400 mt-0.5">{{ $msg->email }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <p class="text-xs {{ !$msg->is_read ? 'font-medium text-slate-200' : 'text-slate-400' }} line-clamp-2">
                                    {{ $msg->message }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-[11px] text-slate-400">
                                <div>{{ $msg->created_at->format('d M Y, H:i') }}</div>
                                <div class="text-[10px] text-slate-500">{{ $msg->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.messages.show', $msg) }}" class="rounded-lg border border-indigo-500/30 bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-300 transition-colors hover:bg-indigo-500/20 hover:text-white">
                                        Baca Pesan
                                    </a>
                                    <form method="POST" action="{{ route('admin.messages.toggle-read', $msg) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}" class="rounded-lg border border-white/10 bg-white/5 p-1.5 text-slate-300 transition-colors hover:bg-white/10 hover:text-white">
                                            @if($msg->is_read)
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            @else
                                                <svg class="h-4 w-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Pesan" class="rounded-lg border border-rose-500/20 bg-rose-500/10 p-1.5 text-rose-300 transition-colors hover:bg-rose-500/20">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Belum ada pesan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
