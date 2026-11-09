@extends('admin.layouts.app')

@section('title', 'Kelola Projek')
@section('page_title', 'Daftar Projek')

@section('content')
<div class="space-y-6">
    <!-- Header Bar with Search & Add Button -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul projek..."
                    class="h-10 w-64 rounded-xl border border-white/10 bg-[#0d1322] px-3.5 pr-9 text-xs text-slate-100 placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                >
                @if(request('search'))
                    <a href="{{ route('admin.projects.index') }}" class="absolute right-3 top-2.5 text-slate-500 hover:text-white text-xs">✕</a>
                @endif
            </div>

            <select
                name="featured"
                onchange="this.form.submit()"
                class="h-10 rounded-xl border border-white/10 bg-[#0d1322] px-3 text-xs text-slate-200 focus:border-indigo-500 focus:outline-none"
            >
                <option value="">Semua Status</option>
                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Non-Featured</option>
            </select>
        </form>

        <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-4 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Projek Baru
        </a>
    </div>

    <!-- Projects Table Card -->
    <div class="overflow-hidden rounded-3xl border border-white/10 bg-[#0d1322]/80 shadow-2xl backdrop-blur-xl">
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
                        <tr class="transition-colors hover:bg-white/[0.02]">
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
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data projek yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
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

