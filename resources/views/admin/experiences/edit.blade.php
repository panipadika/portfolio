@extends('admin.layouts.app')

@section('title', 'Edit Riwayat')
@section('page_title', 'Edit Riwayat Karir / Edukasi')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.experiences.index', ['type' => $experience->type]) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
            <span>←</span> Kembali ke daftar riwayat
        </a>
    </div>

    <form method="POST" action="{{ route('admin.experiences.update', $experience) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <h2 class="font-display text-lg font-bold text-white border-b border-white/10 pb-4">Detail Riwayat: {{ $experience->institution }}</h2>

            <!-- Type Selector -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Tipe Riwayat <span class="text-rose-400">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center gap-3 p-4 rounded-2xl border border-white/10 bg-slate-900/60 cursor-pointer hover:border-indigo-500/50 transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-600/10">
                        <input type="radio" name="type" value="experience" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4" {{ old('type', $experience->type) === 'experience' ? 'checked' : '' }}>
                        <div>
                            <span class="block text-sm font-semibold text-white">Pengalaman Kerja</span>
                            <span class="block text-[11px] text-slate-400">Pekerjaan, magang, freelance, dsb.</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-3 p-4 rounded-2xl border border-white/10 bg-slate-900/60 cursor-pointer hover:border-cyan-500/50 transition-all has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-600/10">
                        <input type="radio" name="type" value="education" class="text-cyan-500 focus:ring-cyan-500 h-4 w-4" {{ old('type', $experience->type) === 'education' ? 'checked' : '' }}>
                        <div>
                            <span class="block text-sm font-semibold text-white">Pendidikan Formal</span>
                            <span class="block text-[11px] text-slate-400">Kuliah, sekolah, akademi, dsb.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Institution & Role/Major -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Institusi / Perusahaan <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="institution"
                        value="{{ old('institution', $experience->institution) }}"
                        required
                        placeholder="Contoh: Universitas Siliwangi / PT. Tech"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Peran / Jurusan <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="role_or_major"
                        value="{{ old('role_or_major', $experience->role_or_major) }}"
                        required
                        placeholder="Contoh: S1 Informatika / Web Developer Intern"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Period & Sort Order -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Periode <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="period"
                        value="{{ old('period', $experience->period) }}"
                        required
                        placeholder="Contoh: 2022 - Sekarang / Okt 2024 - Des 2024"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Urutan Tampilan (Sort Order)</label>
                    <input
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', $experience->sort_order) }}"
                        min="0"
                        placeholder="1, 2, 3..."
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Deskripsi / Keterangan (Opsional)</label>
                <textarea
                    name="description"
                    rows="3"
                    placeholder="Jelaskan tanggung jawab utama, pencapaian, atau bidang studi yang dipelajari..."
                    class="w-full rounded-xl border border-white/10 bg-slate-900/80 p-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                >{{ old('description', $experience->description) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.experiences.index', ['type' => $experience->type]) }}" class="rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-xs font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-6 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                Perbarui Riwayat
            </button>
        </div>
    </form>
</div>
@endsection

