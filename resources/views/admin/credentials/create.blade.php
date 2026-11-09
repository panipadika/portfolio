@extends('admin.layouts.app')

@section('title', 'Tambah Kredensial Baru')
@section('page_title', 'Tambah Kredensial Baru')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.credentials.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
            <span>←</span> Kembali ke daftar kredensial
        </a>
    </div>

    <form method="POST" action="{{ route('admin.credentials.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <h2 class="font-display text-lg font-bold text-white border-b border-white/10 pb-4">Detail Kredensial</h2>

            <!-- Category Selector -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Kategori Kredensial <span class="text-rose-400">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="relative flex items-center gap-3 p-4 rounded-2xl border border-white/10 bg-slate-900/60 cursor-pointer hover:border-blue-500/50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-600/10">
                        <input type="radio" name="category" value="certificate" class="text-blue-500 focus:ring-blue-500 h-4 w-4" {{ old('category', $defaultCategory) === 'certificate' ? 'checked' : '' }}>
                        <div>
                            <span class="block text-sm font-semibold text-white">Sertifikat</span>
                            <span class="block text-[11px] text-slate-400">Kursus, sertifikasi</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-3 p-4 rounded-2xl border border-white/10 bg-slate-900/60 cursor-pointer hover:border-amber-500/50 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-600/10">
                        <input type="radio" name="category" value="achievement" class="text-amber-500 focus:ring-amber-500 h-4 w-4" {{ old('category', $defaultCategory) === 'achievement' ? 'checked' : '' }}>
                        <div>
                            <span class="block text-sm font-semibold text-white">Prestasi</span>
                            <span class="block text-[11px] text-slate-400">Kompetisi, penghargaan</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-3 p-4 rounded-2xl border border-white/10 bg-slate-900/60 cursor-pointer hover:border-purple-500/50 transition-all has-[:checked]:border-purple-500 has-[:checked]:bg-purple-600/10">
                        <input type="radio" name="category" value="tech_stack" class="text-purple-500 focus:ring-purple-500 h-4 w-4" {{ old('category', $defaultCategory) === 'tech_stack' ? 'checked' : '' }}>
                        <div>
                            <span class="block text-sm font-semibold text-white">Tech Stack</span>
                            <span class="block text-[11px] text-slate-400">Keahlian bahasa & tools</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Title & Issuer / Level -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Nama Kredensial / Bahasa / Prestasi <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Contoh: Belajar Dasar Pemrograman Web"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Penerbit / Tingkat / Level (Opsional)</label>
                    <input
                        type="text"
                        name="issuer_or_level"
                        value="{{ old('issuer_or_level') }}"
                        placeholder="Contoh: Dicoding / Juara 1 / Advanced"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Year & Sort Order -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Tahun / Periode (Opsional)</label>
                    <input
                        type="text"
                        name="year"
                        value="{{ old('year') }}"
                        placeholder="Contoh: 2024"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Urutan Tampilan (Sort Order)</label>
                    <input
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order') }}"
                        min="0"
                        placeholder="1, 2, 3... (opsional)"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Credential URL -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Tautan Verifikasi / Portofolio URL (Opsional)</label>
                <input
                    type="url"
                    name="credential_url"
                    value="{{ old('credential_url') }}"
                    placeholder="https://dicoding.com/certificates/XYZ atau link bukti"
                    class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                >
            </div>

            <!-- Image File Upload / Icon URL / Devicon & Iconify SVG -->
            <div class="space-y-4 pt-2 border-t border-white/10">
                <label class="block text-xs font-semibold text-slate-300">Gambar Sertifikat / Logo / Ikon (Devicon & Iconify Ready)</label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- File Upload -->
                    <div>
                        <span class="block text-[11px] text-slate-400 mb-2">Unggah File Gambar (Disarankan untuk Sertifikat):</span>
                        <label class="flex flex-col items-center justify-center border-2 border-dashed border-white/20 rounded-2xl p-4 cursor-pointer hover:border-indigo-500/50 bg-white/[0.01] hover:bg-white/[0.03] transition-all">
                            <svg class="h-8 w-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs text-slate-300 font-medium">Pilih file gambar</span>
                            <span class="text-[10px] text-slate-500 mt-1">PNG, JPG, WebP, SVG (Maks. 5MB)</span>
                            <input type="file" name="image" accept="image/*,.svg" class="hidden" onchange="previewCredentialImage(event)">
                        </label>
                    </div>

                    <!-- Direct SVG Paste, Iconify identifier, or URL -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="block text-[11px] text-slate-400">Atau Gunakan SVG / Iconify / URL Gambar:</span>
                            <span class="text-[10px] text-cyan-400 font-mono">Devicon / Iconify ✓</span>
                        </div>
                        <textarea
                            id="iconCodeInput"
                            name="image_or_icon"
                            rows="4"
                            placeholder='Contoh yang didukung:&#10;1. Paste kode SVG: <svg viewBox="0 0 128 128">...</svg>&#10;2. Nama Iconify: devicon:react atau logos:vue&#10;3. Tautan URL: https://.../icon.svg'
                            class="w-full rounded-xl border border-white/10 bg-slate-900/80 p-3 text-xs font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none leading-relaxed selection:bg-indigo-500/30"
                            oninput="previewSvgOrIcon(this.value)"
                        >{{ old('image_or_icon') }}</textarea>

                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-[11px]">
                            <span class="text-slate-400">
                                💡 Cari ikon resmi:
                                <a href="https://devicon.dev" target="_blank" rel="noopener noreferrer" class="text-cyan-400 hover:underline font-semibold ml-1">Devicon ↗</a>
                                <span class="text-slate-600 mx-1">•</span>
                                <a href="https://icon-sets.iconify.design" target="_blank" rel="noopener noreferrer" class="text-cyan-400 hover:underline font-semibold">Iconify ↗</a>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Box -->
                <div id="imagePreviewContainer" class="hidden mt-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-semibold text-slate-400">Pratinjau Ikon / Gambar:</span>
                        <span id="previewTypeBadge" class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">SVG / Image</span>
                    </div>
                    <div class="h-28 w-28 rounded-xl overflow-hidden border border-white/10 bg-slate-950 flex items-center justify-center p-3 shadow-inner">
                        <div id="svgPreviewWrapper" class="h-full w-full flex items-center justify-center [&>svg]:h-full [&>svg]:w-full [&>svg]:max-h-full [&>svg]:max-w-full">
                            <img id="imagePreview" src="#" alt="Pratinjau" class="max-h-full max-w-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.credentials.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-6 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                Simpan Kredensial
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewCredentialImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.getElementById('svgPreviewWrapper');
                wrapper.innerHTML = '<img id="imagePreview" src="' + e.target.result + '" alt="Pratinjau" class="max-h-full max-w-full object-contain">';
                document.getElementById('previewTypeBadge').textContent = 'File (' + file.name.split('.').pop().toUpperCase() + ')';
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function previewSvgOrIcon(value) {
        const trimmed = (value || '').trim();
        const container = document.getElementById('imagePreviewContainer');
        const wrapper = document.getElementById('svgPreviewWrapper');
        const badge = document.getElementById('previewTypeBadge');

        if (!trimmed) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');

        // 1. Raw SVG
        if (trimmed.startsWith('<svg')) {
            wrapper.innerHTML = trimmed;
            badge.textContent = 'SVG Markup';
            return;
        }

        // 2. Iconify identifier (e.g. devicon:react or logos:vue)
        if (trimmed.includes(':') && !trimmed.startsWith('http')) {
            const parts = trimmed.split(':');
            const iconUrl = 'https://api.iconify.design/' + parts[0] + '/' + parts[1] + '.svg';
            wrapper.innerHTML = '<img src="' + iconUrl + '" alt="Iconify" class="max-h-full max-w-full object-contain" onerror="this.alt=\'Gagal memuat ikon\'">';
            badge.textContent = 'Iconify (' + trimmed + ')';
            return;
        }

        // 3. Direct URL or File Path
        if (trimmed.startsWith('http://') || trimmed.startsWith('https://') || trimmed.startsWith('/')) {
            wrapper.innerHTML = '<img src="' + trimmed + '" alt="URL" class="max-h-full max-w-full object-contain" onerror="this.alt=\'Gagal memuat gambar\'">';
            badge.textContent = 'URL Gambar/SVG';
            return;
        }

        wrapper.innerHTML = '<div class="text-xs font-mono font-bold text-indigo-400 text-center">' + trimmed + '</div>';
        badge.textContent = 'Kode Ikon';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const iconInput = document.getElementById('iconCodeInput');
        if (iconInput && iconInput.value.trim()) {
            previewSvgOrIcon(iconInput.value);
        }
    });
</script>
@endpush
@endsection

