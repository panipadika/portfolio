@extends('admin.layouts.app')

@section('title', 'Pengaturan Kontak & Sosial Media')
@section('page_title', 'Pengaturan Kontak & Media Sosial')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="rounded-2xl border border-white/10 bg-indigo-500/5 p-4 text-xs text-indigo-200 flex items-start gap-3">
        <svg class="h-5 w-5 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <span class="font-semibold block text-indigo-300">Sinkronisasi Dinamis dengan Frontend:</span>
            Seluruh data kontak dan tautan sosial media di halaman ini otomatis disalurkan ke frontend melalui endpoint <code class="font-mono bg-indigo-950/60 px-1 py-0.5 rounded text-[11px] text-indigo-200">GET /api/portfolio</code>, sehingga tombol WhatsApp, email, dan ikon sosial media di halaman utama akan selalu terhubung secara real-time.
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Contact Information Section -->
        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="h-9 w-9 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Informasi Kontak & Lokasi</h2>
                    <p class="text-[11px] text-slate-400">Digunakan pada tombol Hubungi Saya dan footer portofolio</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Contact Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Email Kontak Publik</label>
                    <input
                        type="email"
                        name="contact_email"
                        value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                        placeholder="contoh@email.com"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Nomor Telepon / WhatsApp</label>
                    <input
                        type="text"
                        name="contact_phone"
                        value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                        placeholder="+62 812-3456-7890"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Contact WhatsApp URL -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Link Langsung WhatsApp</label>
                    <input
                        type="text"
                        name="contact_whatsapp"
                        value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}"
                        placeholder="https://wa.me/6281234567890"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Lokasi / Domisili</label>
                    <input
                        type="text"
                        name="contact_location"
                        value="{{ old('contact_location', $settings['contact_location'] ?? '') }}"
                        placeholder="Tasikmalaya, Jawa Barat, Indonesia"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Bio / Headline -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Bio Singkat / Headline Hubungi Saya</label>
                <textarea
                    name="bio_headline"
                    rows="2"
                    placeholder="Tertarik berkolaborasi atau memiliki pertanyaan? Jangan ragu untuk menghubungi saya!"
                    class="w-full rounded-xl border border-white/10 bg-slate-900/80 p-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                >{{ old('bio_headline', $settings['bio_headline'] ?? '') }}</textarea>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
            <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                <div class="h-9 w-9 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-bold text-white">Tautan Media Sosial</h2>
                    <p class="text-[11px] text-slate-400">Tautan profil pengembang yang muncul pada ID Card Lanyard & Footer</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- GitHub -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">GitHub Profile URL</label>
                    <input
                        type="url"
                        name="social_github"
                        value="{{ old('social_github', $settings['social_github'] ?? '') }}"
                        placeholder="https://github.com/username"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- LinkedIn -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">LinkedIn Profile URL</label>
                    <input
                        type="url"
                        name="social_linkedin"
                        value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}"
                        placeholder="https://linkedin.com/in/username"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Instagram Profile URL</label>
                    <input
                        type="url"
                        name="social_instagram"
                        value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                        placeholder="https://instagram.com/username"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>

                <!-- TikTok -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">TikTok Profile URL</label>
                    <input
                        type="url"
                        name="social_tiktok"
                        value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"
                        placeholder="https://tiktok.com/@username"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-6 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

