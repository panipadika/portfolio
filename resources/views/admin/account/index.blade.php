@extends('admin.layouts.app')

@section('title', 'Pengaturan Akun Admin')
@section('page_title', 'Pengaturan Akun & Keamanan')

@section('content')
<div class="max-w-4xl space-y-8">
    <!-- Header Notice -->
    <div class="rounded-2xl border border-white/10 bg-indigo-500/5 p-4 text-xs text-indigo-200 flex items-start gap-3">
        <svg class="h-5 w-5 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <div>
            <span class="font-semibold block text-indigo-300">Pengaturan Kredensial Administrator:</span>
            Di halaman ini Anda dapat memperbarui nama akun, email yang digunakan untuk login ke admin panel, serta mengganti password login. Anda juga bisa mengakses panel admin kapan saja melalui tautan ringkas <code class="font-mono bg-indigo-950/60 px-1.5 py-0.5 rounded text-[11px] text-indigo-200">/admin</code>.
        </div>
    </div>

    <!-- Section 1: Update Profile (Name & Email) -->
    <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
            <div class="h-10 w-10 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h2 class="font-display text-base font-bold text-white">Informasi Akun & Email Login</h2>
                <p class="text-[11px] text-slate-400">Ubah nama identitas admin dan email utama untuk masuk ke sistem</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.account.profile') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name / Username -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Nama Akun / Username <span class="text-rose-400">*</span></label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        placeholder="Contoh: Pani Padika"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Login -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Email Login <span class="text-rose-400">*</span></label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        placeholder="panipadika123@gmail.com"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <span class="text-[11px] text-slate-500 font-mono">Status Email: Terverifikasi ✓</span>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#262f6b] via-indigo-600 to-[#7185e8] px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Update Password -->
    <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
            <div class="h-10 w-10 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <div>
                <h2 class="font-display text-base font-bold text-white">Ganti Password Akun</h2>
                <p class="text-[11px] text-slate-400">Pastikan menggunakan kombinasi password yang kuat dan aman</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.account.password') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Password Saat Ini <span class="text-rose-400">*</span></label>
                <input
                    type="password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password yang sedang digunakan"
                    class="h-11 w-full max-w-md rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                >
                @error('current_password')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- New Password -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Password Baru <span class="text-rose-400">*</span></label>
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Ulangi Password Baru <span class="text-rose-400">*</span></label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ketik ulang password baru"
                        class="h-11 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    >
                </div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

    <!-- Section 3: Custom Login URL Path -->
    <div class="rounded-3xl border border-white/10 bg-[#0d1322]/80 p-6 sm:p-8 shadow-2xl backdrop-blur-xl space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
            <div class="h-10 w-10 rounded-xl bg-cyan-600/20 text-cyan-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
            <div>
                <h2 class="font-display text-base font-bold text-white">Kustomisasi URL Login Admin</h2>
                <p class="text-[11px] text-slate-400">Ubah alamat URL khusus untuk mengakses halaman login dashboard admin</p>
            </div>
        </div>

        <!-- Current URL Preview Card -->
        <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="space-y-1">
                <span class="text-[10px] uppercase font-mono tracking-wider text-slate-500">URL Akses Login Saat Ini</span>
                <div class="font-mono text-sm text-cyan-300 font-semibold flex items-center gap-2">
                    <span>{{ url('/' . $loginSlug) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('/' . $loginSlug) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white">
                    <span>Buka Tautan</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.account.login-slug') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Slug URL Login Baru <span class="text-rose-400">*</span></label>
                <div class="flex items-center rounded-xl border border-white/10 bg-slate-900/80 overflow-hidden focus-within:border-cyan-500 focus-within:ring-1 focus-within:ring-cyan-500">
                    <span class="px-4 py-2.5 bg-white/5 text-xs font-mono text-slate-400 border-r border-white/10 select-none">{{ url('/') }}/</span>
                    <input
                        type="text"
                        name="login_slug"
                        value="{{ old('login_slug', $loginSlug) }}"
                        required
                        placeholder="admin atau portal-admin"
                        class="h-11 flex-1 bg-transparent px-4 text-sm font-mono text-white placeholder:text-slate-500 focus:outline-none"
                    >
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400">
                    Hanya gunakan huruf kecil, angka, strip (-), atau garis bawah (_). Contoh: <span class="font-mono text-cyan-400">admin</span>, <span class="font-mono text-cyan-400">portal-admin</span>, atau <span class="font-mono text-cyan-400">masuk</span>.
                </p>
                @error('login_slug')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-cyan-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan URL Login Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

