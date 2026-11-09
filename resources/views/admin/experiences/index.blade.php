@extends('admin.layouts.app')

@section('title', 'Kelola Riwayat Karir & Edukasi')
@section('page_title', 'Edukasi & Pengalaman')

@section('content')
<div class="space-y-6">
    <!-- Header with Type Tabs & Add Button -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Type Filter Tabs -->
        <div class="inline-flex rounded-2xl border border-white/10 bg-[#0d1322] p-1 shadow-lg">
            <a href="{{ route('admin.experiences.index') }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ empty($type) ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400 hover:text-white' }}">
                Semua ({{ $educationCount + $experienceCount }})
            </a>
            <a href="{{ route('admin.experiences.index', ['type' => 'experience']) }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ $type === 'experience' ? 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/30' : 'text-slate-400 hover:text-white' }}">
                Pengalaman Kerja ({{ $experienceCount }})
            </a>
            <a href="{{ route('admin.experiences.index', ['type' => 'education']) }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ $type === 'education' ? 'bg-cyan-600/20 text-cyan-300 border border-cyan-500/30' : 'text-slate-400 hover:text-white' }}">
                Pendidikan ({{ $educationCount }})
            </a>
        </div>

        <a href="{{ route('admin.experiences.create', ['type' => $type ?: 'experience']) }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Riwayat
        </a>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="border-b border-white/10 bg-white/[0.02] font-mono text-[11px] uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Institusi / Perusahaan</th>
                        <th class="px-6 py-4">Peran / Jurusan</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 font-sans">
                    @forelse($items as $item)
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->type === 'experience')
                                    <span class="inline-flex items-center rounded-full bg-emerald-500/20 px-2.5 py-1 text-[11px] font-bold text-emerald-300 border border-emerald-500/30 whitespace-nowrap">Pengalaman</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-cyan-500/20 px-2.5 py-1 text-[11px] font-bold text-cyan-300 border border-cyan-500/30 whitespace-nowrap">Pendidikan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-sm text-white">{{ $item->institution }}</div>
                                @if($item->description)
                                    <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $item->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-200">
                                {{ $item->role_or_major }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-300">
                                {{ $item->period }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-400">
                                #{{ $item->sort_order }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.experiences.edit', $item) }}" class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-semibold text-slate-200 transition-colors hover:bg-white/10 hover:text-white">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.experiences.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-500/20 bg-rose-500/10 px-2.5 py-1.5 text-xs font-semibold text-rose-300 transition-colors hover:bg-rose-500/20">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data riwayat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

