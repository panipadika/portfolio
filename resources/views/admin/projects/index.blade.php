@extends('admin.layouts.app')

@section('title', 'Kelola Projek')
@section('page_title', 'Daftar Projek')

@section('content')
<div class="space-y-6">
    <!-- Header Bar with Live Search, Filter & Add Button -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form id="projectFilterForm" method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative w-full sm:w-72">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    id="projectLiveSearch"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul, slug, atau teknologi..."
                    autocomplete="off"
                    class="h-10 w-full rounded-xl border border-white/10 bg-[#0d1322] pl-9 pr-14 text-xs text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner"
                >
                <span id="projectSearchLoading" class="pointer-events-none absolute right-7 top-2.5 hidden h-4 w-4 animate-spin rounded-full border-2 border-indigo-400/30 border-t-indigo-400"></span>
                <button
                    type="button"
                    id="projectClearSearch"
                    class="absolute right-2.5 top-2.5 text-slate-500 hover:text-white text-xs cursor-pointer {{ empty(request('search')) ? 'hidden' : '' }}"
                    title="Hapus pencarian"
                >✕</button>
            </div>

            <select
                id="projectFeaturedFilter"
                name="featured"
                class="h-10 rounded-xl border border-white/10 bg-[#0d1322] px-3 text-xs text-slate-200 focus:border-indigo-500 focus:outline-none"
            >
                <option value="">Semua Status</option>
                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Non-Featured</option>
            </select>
        </form>

        <a href="{{ route('admin.projects.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Projek Baru
        </a>
    </div>

    <!-- Projects Table Card -->
    <div id="projectsResultsContainer" class="overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="border-b border-white/10 bg-white/[0.02] font-mono text-[11px] uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Thumbnail</th>
                        <th class="px-6 py-4">Judul & Slug</th>
                        <th class="px-6 py-4">Teknologi</th>
                        <th class="px-6 py-4">Tautan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 font-sans">
                    @forelse($projects as $project)
                        @php
                            $projectTags = is_array($project->tech_tags) ? implode(' ', $project->tech_tags) : '';
                            $projectStatusLabel = $project->is_featured ? 'featured unggulan' : 'standard non-featured';
                        @endphp
                        <tr
                            class="transition-colors hover:bg-white/[0.02] project-item-row"
                            data-featured="{{ $project->is_featured ? '1' : '0' }}"
                            data-search="{{ strtolower(trim($project->title . ' ' . $project->slug . ' ' . ($project->description ?? '') . ' ' . $projectTags . ' ' . $projectStatusLabel)) }}"
                        >
                            <td class="px-6 py-4">
                                <div class="h-12 w-20 rounded-xl border border-white/10 bg-slate-900/80 overflow-hidden">
                                    @if($project->image_url)
                                        <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-slate-600 text-[10px]">No image</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-sm text-white">{{ $project->title }}</div>
                                <div class="font-mono text-[11px] text-slate-500">/{{ $project->slug }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @if(is_array($project->tech_tags))
                                        @foreach($project->tech_tags as $tag)
                                            <span class="rounded-md bg-white/5 px-2 py-0.5 text-[10px] font-mono text-slate-300 border border-white/5">{{ $tag }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($project->demo_url)
                                        <a href="{{ $project->demo_url }}" target="_blank" class="text-[#7185e8] hover:text-white" title="Live Demo">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @endif
                                    @if($project->repo_url)
                                        <a href="{{ $project->repo_url }}" target="_blank" class="text-slate-400 hover:text-white" title="Repository">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->is_featured)
                                    <span class="inline-flex items-center rounded-full bg-indigo-500/20 px-2.5 py-1 text-[11px] font-bold text-indigo-300 border border-indigo-500/30 whitespace-nowrap">Featured</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-[11px] text-slate-400 border border-slate-700 whitespace-nowrap">Standard</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-400 whitespace-nowrap">
                                #{{ $project->sort_order }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="rounded-lg border border-white/10 bg-white/5 px-2.5 py-1.5 text-xs font-semibold text-slate-200 transition-colors hover:bg-white/10 hover:text-white">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus projek ini?')">
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
                        <tr id="projectServerEmptyRow">
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data projek yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                    <tr id="projectNoResultsRow" style="display: none;">
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <span class="inline-flex items-center gap-2 text-slate-400">
                                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Tidak ada projek yang cocok dengan pencarian Anda.
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="border-t border-white/10 px-6 py-4">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('projectFilterForm');
        const searchInput = document.getElementById('projectLiveSearch');
        const featuredSelect = document.getElementById('projectFeaturedFilter');
        const clearBtn = document.getElementById('projectClearSearch');
        const loadingSpinner = document.getElementById('projectSearchLoading');
        const resultsContainer = document.getElementById('projectsResultsContainer');

        if (!searchInput || !resultsContainer) return;

        const baseUrl = @json(route('admin.projects.index', [], false));
        const requiresServerSync = {{ ($projects->total() > $projects->perPage() || request()->filled('search') || request()->filled('featured')) ? 'true' : 'false' }};
        let debounceTimer = null;
        let activeController = null;
        let lastSyncedQuery = new URLSearchParams(window.location.search).toString();

        const buildQueryParams = () => {
            const params = new URLSearchParams();
            const q = searchInput.value.trim();
            const featured = featuredSelect ? featuredSelect.value : '';
            if (q !== '') params.set('search', q);
            if (featured !== '') params.set('featured', featured);
            return params;
        };

        const applyClientFilter = () => {
            const term = searchInput.value.toLowerCase().trim();
            const featured = featuredSelect ? featuredSelect.value : '';
            const rows = resultsContainer.querySelectorAll('.project-item-row');
            const noResultsRow = document.getElementById('projectNoResultsRow');
            const serverEmptyRow = document.getElementById('projectServerEmptyRow');
            let matches = 0;

            rows.forEach((row) => {
                const searchData = row.getAttribute('data-search') || '';
                const rowFeatured = row.getAttribute('data-featured') || '';
                const matchText = term === '' || searchData.includes(term);
                const matchStatus = featured === '' || rowFeatured === featured;
                const visible = matchText && matchStatus;
                row.style.display = visible ? '' : 'none';
                if (visible) matches++;
            });

            if (clearBtn) {
                clearBtn.classList.toggle('hidden', searchInput.value.trim().length === 0);
            }

            if (noResultsRow) {
                noResultsRow.style.display = (matches === 0 && rows.length > 0 && !requiresServerSync) ? '' : 'none';
            }

            if (serverEmptyRow) {
                serverEmptyRow.style.display = (rows.length === 0) ? '' : 'none';
            }
        };

        const syncFromServer = async () => {
            const params = buildQueryParams();
            const queryString = params.toString();
            const targetUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;

            history.replaceState(null, '', targetUrl);

            if (!requiresServerSync) {
                applyClientFilter();
                return;
            }

            if (queryString === lastSyncedQuery) {
                return;
            }

            if (activeController) {
                activeController.abort();
            }
            activeController = new AbortController();
            if (loadingSpinner) loadingSpinner.classList.remove('hidden');

            try {
                const response = await fetch(targetUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: activeController.signal,
                });
                if (!response.ok) return;

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const nextResults = doc.getElementById('projectsResultsContainer');

                if (nextResults) {
                    resultsContainer.innerHTML = nextResults.innerHTML;
                    lastSyncedQuery = queryString;
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Project live search error:', err);
                }
            } finally {
                if (loadingSpinner) loadingSpinner.classList.add('hidden');
            }
        };

        const scheduleSync = (immediate = false) => {
            applyClientFilter();
            clearTimeout(debounceTimer);

            if (!requiresServerSync) {
                const params = buildQueryParams();
                const qs = params.toString();
                history.replaceState(null, '', qs ? `${baseUrl}?${qs}` : baseUrl);
                return;
            }

            if (immediate) {
                syncFromServer();
            } else {
                debounceTimer = setTimeout(syncFromServer, 220);
            }
        };

        searchInput.addEventListener('input', () => scheduleSync(false));

        if (featuredSelect) {
            featuredSelect.addEventListener('change', () => scheduleSync(true));
        }

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                scheduleSync(true);
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                scheduleSync(true);
                searchInput.focus();
            });
        }
    });
</script>
@endpush

