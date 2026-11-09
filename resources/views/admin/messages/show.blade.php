@extends('admin.layouts.app')

@section('title', 'Detail Pesan dari ' . $message->name)
@section('page_title', 'Detail Pesan Masuk')

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Back & Actions Bar -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
            <span>←</span> Kembali ke kotak masuk
        </a>

        <div class="flex items-center gap-2">
            <!-- Mailto Reply -->
            <a href="mailto:{{ $message->email }}?subject=Re: Kontak Portofolio - {{ rawurlencode($message->name) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Balas via Email
            </a>

            <!-- Toggle Read -->
            <form method="POST" action="{{ route('admin.messages.toggle-read', $message) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white">
                    {{ $message->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                </button>
            </form>

            <!-- Delete -->
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-xl border border-rose-500/20 bg-rose-500/10 px-3 py-2 text-xs font-semibold text-rose-300 transition-colors hover:bg-rose-500/20">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Message Content Box -->
    <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
        <!-- Message Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-white/10 pb-6">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-lg font-bold text-white shadow-lg shadow-indigo-500/20">
                    {{ strtoupper(substr($message->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold text-white">{{ $message->name }}</h2>
                    <a href="mailto:{{ $message->email }}" class="font-mono text-xs text-indigo-400 hover:underline">
                        {{ $message->email }}
                    </a>
                </div>
            </div>

            <div class="text-right">
                <div class="font-mono text-xs text-slate-300">{{ $message->created_at->format('d F Y, H:i:s') }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ $message->created_at->diffForHumans() }}</div>
            </div>
        </div>

        <!-- Message Body -->
        <div class="space-y-2">
            <span class="text-[11px] font-mono uppercase tracking-wider text-slate-400">Isi Pesan:</span>
            <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-6 text-sm text-slate-200 leading-relaxed font-sans whitespace-pre-wrap selection:bg-indigo-500/30">
{{ $message->message }}
            </div>
        </div>

        <!-- Sender Metadata -->
        <div class="rounded-2xl border border-white/5 bg-white/[0.01] p-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono">
            <div>
                <span class="text-slate-500 block text-[10px] uppercase">Alamat IP Pengirim</span>
                <span class="text-slate-300 font-semibold">{{ $message->ip_address ?: 'Tidak tercatat' }}</span>
            </div>
            <div>
                <span class="text-slate-500 block text-[10px] uppercase">Status Baca</span>
                @if($message->is_read)
                    <span class="text-emerald-400 font-semibold">Sudah Dibaca</span>
                @else
                    <span class="text-amber-400 font-semibold">Belum Dibaca</span>
                @endif
            </div>
            <div>
                <span class="text-slate-500 block text-[10px] uppercase">Waktu Diterima</span>
                <span class="text-slate-300 font-semibold">{{ $message->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i T') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
