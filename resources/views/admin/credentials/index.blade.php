@extends('admin.layouts.app')

@section('title', 'Kelola Sertifikat, Prestasi & Tech Stack')
@section('page_title', 'Sertifikat & Kredensial')

@section('content')
<div class="space-y-6">
    <!-- Header with Category Tabs, Live Search & Add Button -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <!-- Category Filter Tabs -->
        <div class="inline-flex flex-wrap sm:flex-nowrap shrink-0 max-w-full overflow-x-auto rounded-2xl border border-white/10 bg-[#0d1322] p-1 shadow-lg">
            <a href="{{ route('admin.credentials.index', array_filter(['search' => $search ?? request('search')])) }}" class="rounded-xl px-3.5 py-2 text-xs font-semibold whitespace-nowrap transition-all {{ empty($category) ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-400 hover:text-white' }}">
                Semua ({{ $certificateCount + $achievementCount + $techStackCount }})
            </a>
            <a href="{{ route('admin.credentials.index', array_filter(['category' => 'certificate', 'search' => $search ?? request('search')])) }}" class="rounded-xl px-3.5 py-2 text-xs font-semibold whitespace-nowrap transition-all {{ $category === 'certificate' ? 'bg-blue-600/20 text-blue-300 border border-blue-500/30' : 'text-slate-400 hover:text-white' }}">
                Sertifikat ({{ $certificateCount }})
            </a>
            <a href="{{ route('admin.credentials.index', array_filter(['category' => 'achievement', 'search' => $search ?? request('search')])) }}" class="rounded-xl px-3.5 py-2 text-xs font-semibold whitespace-nowrap transition-all {{ $category === 'achievement' ? 'bg-amber-600/20 text-amber-300 border border-amber-500/30' : 'text-slate-400 hover:text-white' }}">
                Prestasi ({{ $achievementCount }})
            </a>
            <a href="{{ route('admin.credentials.index', array_filter(['category' => 'tech_stack', 'search' => $search ?? request('search')])) }}" class="rounded-xl px-3.5 py-2 text-xs font-semibold whitespace-nowrap transition-all {{ $category === 'tech_stack' ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-slate-400 hover:text-white' }}">
                Tech Stack ({{ $techStackCount }})
            </a>
        </div>

        <!-- Live Search Bar & Add Button -->
        <div class="flex flex-1 items-center gap-3 lg:justify-end">
            <!-- Search Form / Live Input -->
            <form method="GET" action="{{ route('admin.credentials.index') }}" class="relative w-full max-w-xs sm:max-w-sm">
                @if($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    id="credentialLiveSearch"
                    name="search"
                    value="{{ $search ?? request('search') }}"
                    placeholder="Cari nama skill, sertifikat, penerbit..."
                    autocomplete="off"
                    class="h-10 w-full rounded-xl border border-white/10 bg-[#0d1322] pl-9 pr-8 text-xs text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner"
                >
                <button
                    type="button"
                    id="credentialClearSearch"
                    class="absolute right-2.5 top-2.5 text-slate-500 hover:text-white text-xs cursor-pointer {{ empty($search ?? request('search')) ? 'hidden' : '' }}"
                    title="Hapus pencarian"
                >✕</button>
            </form>

            <a href="{{ route('admin.credentials.create', ['category' => $category ?: 'certificate']) }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="whitespace-nowrap">Tambah Kredensial</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="border-b border-white/10 bg-white/[0.02] font-mono text-[11px] uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Kredensial</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Penerbit / Level</th>
                        <th class="px-6 py-4">Tahun</th>
                        <th class="px-6 py-4">Tautan</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="credentialTableBody" class="divide-y divide-white/5 font-sans">
                    @forelse($items as $item)
                        <tr class="transition-colors hover:bg-white/[0.02] credential-item-row" data-search="{{ strtolower($item->title . ' ' . $item->issuer_or_level . ' ' . $item->category . ' ' . $item->year . ' ' . ($item->category === 'tech_stack' ? 'tech stack keahlian skill' : ($item->category === 'certificate' ? 'sertifikat' : 'prestasi achievement'))) }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($item->image_or_icon)
                                        <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-xl border border-white/10 bg-slate-800 flex items-center justify-center p-1">
                                            @if(str_starts_with($item->image_or_icon, '/') || str_starts_with($item->image_or_icon, 'http'))
                                                <img src="{{ $item->image_or_icon }}" alt="{{ $item->title }}" class="h-full w-full object-contain" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'font-mono text-xs font-bold text-indigo-400\'>{{ strtoupper(substr($item->title, 0, 2)) }}</span>';">
                                            @elseif(str_contains($item->image_or_icon, ':'))
                                                @php
                                                    [$prefix, $iconName] = explode(':', $item->image_or_icon, 2);
                                                @endphp
                                                <img src="https://api.iconify.design/{{ $prefix }}/{{ $iconName }}.svg" alt="{{ $item->title }}" class="h-full w-full object-contain" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'font-mono text-xs font-bold text-indigo-400\'>{{ strtoupper(substr($item->title, 0, 2)) }}</span>';">
                                            @else
                                                <span class="font-mono text-xs font-bold text-indigo-400">{{ substr($item->image_or_icon, 0, 3) }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-400 font-mono text-[11px] font-bold">
                                            {{ strtoupper(substr($item->title, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-sm text-white">{{ $item->title }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->category === 'certificate')
                                    <span class="inline-flex items-center rounded-full bg-blue-500/20 px-2.5 py-1 text-[11px] font-bold text-blue-300 border border-blue-500/30 whitespace-nowrap">Sertifikat</span>
                                @elseif($item->category === 'achievement')
                                    <span class="inline-flex items-center rounded-full bg-amber-500/20 px-2.5 py-1 text-[11px] font-bold text-amber-300 border border-amber-500/30 whitespace-nowrap">Prestasi</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-purple-500/20 px-2.5 py-1 text-[11px] font-bold text-purple-300 border border-purple-500/30 whitespace-nowrap">Tech Stack</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-200 whitespace-nowrap">
                                {{ $item->issuer_or_level ?: '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-300 whitespace-nowrap">
                                {{ $item->year ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->credential_url)
                                    <a href="{{ $item->credential_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs text-indigo-400 hover:text-indigo-300 hover:underline">
                                        <span>Buka Link</span>
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-400 whitespace-nowrap">
                                #{{ $item->sort_order }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.credentials.edit', $item) }}" class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-semibold text-slate-200 transition-colors hover:bg-white/10 hover:text-white">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.credentials.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kredensial ini?')">
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
                        <tr id="credentialServerEmptyRow">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data kredensial.
                            </td>
                        </tr>
                    @endforelse
                    <!-- Empty State for Live Search Filter -->
                    <tr id="credentialNoResultsRow" style="display: none;">
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-sans">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg class="h-8 w-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span class="text-xs">Tidak ada kredensial yang cocok dengan kata kunci pencarian.</span>
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
        const searchInput = document.getElementById('credentialLiveSearch');
        const clearBtn = document.getElementById('credentialClearSearch');
        const rows = document.querySelectorAll('.credential-item-row');
        const noResultsRow = document.getElementById('credentialNoResultsRow');
        const serverEmptyRow = document.getElementById('credentialServerEmptyRow');

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

