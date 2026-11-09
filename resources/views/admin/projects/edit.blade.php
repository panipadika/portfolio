@extends('admin.layouts.app')

@section('title', 'Edit Projek: ' . $project->title)
@section('page_title', 'Edit Projek')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
            <span>←</span> Kembali ke daftar projek
        </a>
    </div>

    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <h2 class="font-display text-lg font-bold text-white border-b border-white/10 pb-4">Informasi Dasar Projek</h2>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Judul Projek <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $project->title) }}"
                        required
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Slug URL</label>
                    <input
                        type="text"
                        name="slug"
                        value="{{ old('slug', $project->slug) }}"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Deskripsi Projek <span class="text-rose-400">*</span></label>
                <textarea
                    name="description"
                    rows="4"
                    required
                    class="w-full rounded-xl border border-white/10 bg-slate-900/80 p-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                >{{ old('description', $project->description) }}</textarea>
            </div>

            <!-- Tech Tags (Interactive Picker & Auto Creator) -->
            @include('admin.projects.partials.tech-picker')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <!-- Demo URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Live Demo URL</label>
                    <input
                        type="url"
                        name="demo_url"
                        value="{{ old('demo_url', $project->demo_url) }}"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Repo URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Repository URL</label>
                    <input
                        type="url"
                        name="repo_url"
                        value="{{ old('repo_url', $project->repo_url) }}"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Sort Order -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Urutan Tampilan (Sort Order)</label>
                    <input
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', $project->sort_order) }}"
                        min="0"
                        placeholder="1, 2, 3..."
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Featured Checkbox -->
            <div class="pt-2">
                <label class="relative flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        {{ old('is_featured', $project->is_featured ? '1' : '0') === '1' ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-white/20 bg-slate-900 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0"
                    >
                    <span class="text-sm font-medium text-slate-200">Tampilkan di Showcase Beranda (Featured Project)</span>
                </label>
            </div>
        </div>

        <!-- Image Upload Card -->
        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <h2 class="font-display text-lg font-bold text-white border-b border-white/10 pb-4">Gambar Thumbnail Projek</h2>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 items-start">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Ganti File Gambar</label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        onchange="previewImage(event)"
                        class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/20 file:text-indigo-200 hover:file:bg-indigo-600/30 file:cursor-pointer"
                    >
                    <p class="mt-2 text-[11px] text-slate-400">Kosongkan jika tidak ingin mengubah gambar.</p>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Atau URL Gambar Eksternal</label>
                        <input
                            type="text"
                            name="image_url"
                            value="{{ old('image_url', $project->image_url) }}"
                            class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-xs font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                        >
                    </div>
                </div>

                <!-- Preview Box -->
                <div>
                    <span class="block text-xs font-semibold text-slate-300 mb-2">Pratinjau Gambar Saat Ini</span>
                    <div class="flex h-44 w-full items-center justify-center rounded-2xl border border-dashed border-white/20 bg-slate-900/60 overflow-hidden" id="previewContainer">
                        @if($project->image_url)
                            <img id="imagePreview" src="{{ $project->image_url }}" alt="{{ $project->title }}" class="h-full w-full object-cover">
                            <span id="previewPlaceholder" class="hidden text-xs text-slate-500">Pilih gambar untuk melihat pratinjau</span>
                        @else
                            <img id="imagePreview" src="" alt="Pratinjau" class="hidden h-full w-full object-cover">
                            <span id="previewPlaceholder" class="text-xs text-slate-500">Belum ada gambar</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button Bar -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.projects.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-colors">
                Batal
            </a>
            <button type="submit" class="cursor-pointer rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-6 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/25 transition-all hover:scale-[1.02] active:scale-[0.98]">
                Perbarui Projek
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('imagePreview');
                const placeholder = document.getElementById('previewPlaceholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush

