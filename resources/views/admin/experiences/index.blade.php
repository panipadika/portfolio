@extends('admin.layouts.app')

@section('title', 'Kelola Riwayat Karir & Edukasi')
@section('page_title', 'Edukasi & Pengalaman')

@section('content')
<div class="space-y-6">
    <!-- Header with Type Tabs, Live Search & Add Button -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <!-- Type Filter Tabs -->
        <div class="inline-flex shrink-0 rounded-2xl border border-white/10 bg-[#0d1322] p-1 shadow-lg">
            <a href="{{ route('admin.experiences.index', array_filter(['search' => $search ?? request('search')])) }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ empty($type) ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400 hover:text-white' }}">
                Semua ({{ $educationCount + $experienceCount }})
            </a>
            <a href="{{ route('admin.experiences.index', array_filter(['type' => 'experience', 'search' => $search ?? request('search')])) }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ $type === 'experience' ? 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/30' : 'text-slate-400 hover:text-white' }}">
                Pengalaman Kerja ({{ $experienceCount }})
            </a>
            <a href="{{ route('admin.experiences.index', array_filter(['type' => 'education', 'search' => $search ?? request('search')])) }}" class="rounded-xl px-4 py-2 text-xs font-semibold transition-all {{ $type === 'education' ? 'bg-cyan-600/20 text-cyan-300 border border-cyan-500/30' : 'text-slate-400 hover:text-white' }}">
                Pendidikan ({{ $educationCount }})
            </a>
        </div>

        <!-- Live Search Bar & Add Button -->
        <div class="flex flex-1 items-center gap-3 lg:justify-end">
            <!-- Search Form / Live Input -->
            <form method="GET" action="{{ route('admin.experiences.index') }}" class="relative w-full max-w-xs sm:max-w-sm">
                @if($type)
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    id="experienceLiveSearch"
                    name="search"
                    value="{{ $search ?? request('search') }}"
                    placeholder="Cari institusi, peran, jurusan..."
                    autocomplete="off"
                    class="h-10 w-full rounded-xl border border-white/10 bg-[#0d1322] pl-9 pr-8 text-xs text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner"
                >
                <button
                    type="button"
                    id="experienceClearSearch"
                    class="absolute right-2.5 top-2.5 text-slate-500 hover:text-white text-xs cursor-pointer {{ empty($search ?? request('search')) ? 'hidden' : '' }}"
                    title="Hapus pencarian"
                >✕</button>
            </form>

            <a href="{{ route('admin.experiences.create', ['type' => $type ?: 'experience']) }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="whitespace-nowrap">Tambah Riwayat</span>
            </a>
        </div>
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
                <tbody id="experienceTableBody" class="divide-y divide-white/5 font-sans">
                    @forelse($items as $item)
                        <tr class="transition-colors hover:bg-white/[0.02] experience-item-row" data-search="{{ strtolower($item->institution . ' ' . $item->role_or_major . ' ' . $item->description . ' ' . $item->period . ' ' . ($item->type === 'experience' ? 'pengalaman kerja' : 'pendidikan')) }}">
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
                        <tr id="experienceServerEmptyRow">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data riwayat.
                            </td>
                        </tr>
                    @endforelse
                    <!-- Empty State for Live Search Filter -->
                    <tr id="experienceNoResultsRow" style="display: none;">
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-sans">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="h-8 w-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span class="text-xs">Tidak ada riwayat yang cocok dengan kata kunci pencarian.</span>
                            </div>
                        </td>
                    </tr>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('experienceLiveSearch');
        const clearBtn = document.getElementById('experienceClearSearch');
        const rows = document.querySelectorAll('.experience-item-row');
        const noResultsRow = document.getElementById('experienceNoResultsRow');
        const serverEmptyRow = document.getElementById('experienceServerEmptyRow');

        if (!searchInput) return;

        const performLiveFilter = (query) => {
            const term = query.toLowerCase().trim();
            let matches = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                const isMatch = term === '' || searchData.includes(term);
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) matches++;
            });

            if (clearBtn) {
                clearBtn.classList.toggle('hidden', term.length === 0);
            }

            if (noResultsRow) {
                noResultsRow.style.display = (matches === 0 && rows.length > 0) ? '' : 'none';
            }

            if (serverEmptyRow) {
                serverEmptyRow.style.display = (rows.length === 0) ? '' : 'none';
            }
        };

        searchInput.addEventListener('input', (e) => {
            performLiveFilter(e.target.value);
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                performLiveFilter('');
                searchInput.focus();
            });
        }

        // Apply immediately if input prefilled
        if (searchInput.value.trim().length > 0) {
            performLiveFilter(searchInput.value);
        }
    });
</script>
@endpush

