# AGENTS.md — Panduan AI Code Assistant (Antigravity)

> **INSTRUKSI UTAMA UNTUK AI AGENT:**  
> Sebelum membuat, mengubah, atau menyarankan kode apa pun di repositori ini, Anda **WAJIB** memeriksa dan mematuhi keempat dokumen acuan berikut:
> 1. **`PRD.md`**: Acuan fitur utama, alur pengguna, dan *Acceptance Criteria* (Catatan: Proyek ini **TIDAK** menggunakan fitur komentar publik).
> 2. **`ARCHITECTURE.md`**: Acuan struktur monorepo, skema tabel MySQL di phpMyAdmin, dan spesifikasi endpoint REST API.
> 3. **`DESIGN.md`**: Acuan palet warna, tipografi, tata letak Tailwind CSS, dan desain responsif.
> 4. **`AGENTS.md` (Dokumen ini)**: Acuan konvensi kode, aturan pengujian, dan batasan perubahan.

---

## 1. Tech Stack Utama
Proyek ini menggunakan arsitektur **Monorepo** di lingkungan **Laragon**:
* **Backend Framework:** Laravel 13 (PHP 8.5)
* **Database:** MySQL (dikelola melalui phpMyAdmin di Laragon)
* **Frontend Framework:** Inertia.js v3 + React 19 (di-bundle menggunakan Vite + Laravel Vite Plugin)
* **3D & WebGL:** Standalone Three.js Physics Engine (React Bits Lanyard) di `resources/js/three/` (Lanyard.tsx & LanyardCanvas.jsx)
* **Styling & Animasi:** Tailwind CSS v4, Framer Motion, Lucide React (ikon)
* **HTTP Client:** Axios (terkonfigurasi dengan CSRF token Laravel)

---

## 2. Struktur Folder (Monorepo)
Jangan membuat repositori terpisah untuk *frontend* dan *backend*. Ikuti pemetaan direktori berikut:

```text
portfolio/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/       # Controller REST API (PortfolioController, ContactController)
│   │   ├── Requests/              # FormRequest untuk validasi input (StoreContactRequest)
│   │   └── Resources/             # Eloquent API Resources untuk format JSON seragam
│   └── Models/                    # Model Eloquent (Project, Credential, Experience, Message)
├── database/
│   ├── migrations/                # Skema tabel MySQL (projects, credentials, experiences, messages)
│   └── seeders/                   # Seeder data awal portofolio (Projects, Certificates, Tech Stack, Education)
├── public/
│   ├── assets/images/             # Gambar pratinjau proyek & sertifikat
│   ├── card-front.png             # Tekstur depan 3D ID Card
│   ├── card-back.png              # Tekstur belakang 3D ID Card
│   └── band.png                   # Tekstur tali lanyard 3D
├── resources/
│   ├── css/
│   │   └── app.css                # Entry point Tailwind CSS v4 & custom tokens/scrollbar
│   ├── js/
│   │   ├── components/            # Komponen UI React per seksi
│   │   │   ├── common/            # Navbar, Footer, Preloader
│   │   │   ├── sections/          # HeroSection, AboutSection, SpotifySection, PortfolioSection, ContactSection
│   │   │   ├── ui/                # Komponen kecil (ProjectModal, Badge, StatCard)
│   │   │   └── PortfolioApp.jsx   # Root layout Single-Page Application portofolio
│   │   ├── three/                 # Seluruh logika Three.js Standalone Physics Engine
│   │   │   ├── LanyardCanvas.jsx  # Container & canvas wrapper 3D ID Card
│   │   │   └── Lanyard.tsx        # Mesh, tekstur, dan simulasi fisika kartu & tali 3D
│   │   ├── config/                # Konfigurasi aplikasi & tautan profil (socials.js)
│   │   ├── hooks/                 # Custom React Hooks (useAssetPreloader, usePortfolioData, useBackgroundAudio)
│   │   ├── services/              # Konfigurasi Axios & fungsi pemanggilan API (api.js)
│   │   ├── pages/                 # Halaman Inertia (welcome.tsx, dashboard.tsx)
│   │   └── app.tsx                # Entry point createInertiaApp
│   └── views/
│       └── app.blade.php          # File Blade pembungkus Inertia
├── routes/
│   ├── api.php                    # Seluruh route endpoint JSON (/api/portfolio, /api/contact)
│   └── web.php                    # Route Inertia web
├── tests/
│   └── Feature/                   # Automated testing untuk API Laravel
└── vite.config.ts                 # Konfigurasi Vite + React + Inertia
```

---

## 3. Coding Conventions (Konvensi Kode)

### 3.1. Backend (PHP / Laravel)
* **Standar Kode:** Patuhi standar **PSR-12** dan gunakan `declare(strict_types=1);` bila memungkinkan.
* **Validasi Terpisah:** Jangan menulis logika `$request->validate()` di dalam Controller. Gunakan kelas **FormRequest** (`StoreContactRequest`) di `app/Http/Requests/`.
* **Format Respons API:** Selalu kembalikan respons JSON dengan struktur standar:
  ```json
  {
    "status": "success",
    "message": "Pesan deskriptif",
    "data": { ... }
  }
  ```

### 3.2. Frontend (React & Three.js)
* **Komponen:** Gunakan *Functional Components* dengan React Hooks. Hindari *Class Components*.
* **Penamaan:**
  * Nama file dan komponen React: `PascalCase` (contoh: `PortfolioSection.jsx`).
  * Nama *hooks*, variabel, dan fungsi: `camelCase` (contoh: `useAssetPreloader.js`, `handleContactSubmit`).
  * Konstanta global: `UPPER_SNAKE_CASE`.
* **Manajemen Memori Three.js:**
  * Selalu bungkus komponen 3D di dalam `<Suspense fallback={null}>`.
  * Gunakan `useGLTF.preload('/assets/3d/id_card.glb')` untuk mendukung perhitungan persentase pada *Preloader*.
  * Pastikan geometri dan material dibersihkan (*dispose*) saat komponen di-*unmount* jika membuat objek Three.js secara manual.

---

## 4. Aturan Testing (Testing Rules)
Setiap perubahan atau penambahan fitur pada sisi *backend* maupun *frontend* wajib memenuhi pengujian berikut:
1. **API Endpoint Testing (PHPUnit / Pest):**
   * Uji `GET /api/portfolio` memastikan data `projects`, `credentials`, `experiences`, dan `stats` dikembalikan dengan status HTTP `200`.
   * Uji `POST /api/contact` memastikan validasi `name`, `email`, dan `message` berjalan (status `422` jika kosong/email tidak valid, dan status `201` jika berhasil masuk ke tabel `messages`).
2. **Frontend Sanity Check:**
   * Pastikan `npm run build` berjalan tanpa *error* kompilasi JSX atau kebocoran impor modul.
   * Uji *fallback* Preloader dengan mensimulasikan URL aset 3D yang salah untuk memastikan tombol **Coba Lagi** dan **Lanjutkan** muncul.

---

## 5. Batasan Perubahan Kode (Guardrails & Restrictions)
AI Agent **DILARANG KERAS** melakukan hal-hal berikut:
1. **Dilarang Menambahkan Fitur Komentar Publik:** Sesuai keputusan pemilik proyek, jangan membuat tabel, endpoint, atau komponen UI untuk komentar pengunjung.
2. **Dilarang Mengubah Kredensial `.env` Secara Sembarangan:** Jangan menimpa konfigurasi `DB_DATABASE`, `DB_USERNAME`, atau `DB_PASSWORD` milik pengguna di `.env`. Jika butuh variabel baru, tambahkan di `.env.example`.
3. **Dilarang Menyimpan Gambar sebagai BLOB di MySQL:** Semua gambar proyek dan sertifikat harus disimpan di *filesystem* (`public/assets/images/` atau `storage/app/public`) dan direferensikan menggunakan kolom `VARCHAR` di database.
4. **Dilarang Menghapus Proteksi Keamanan:** Jangan menonaktifkan *Rate Limiting* (`throttle`) pada rute pengiriman pesan kontak (`POST /api/contact`).
5. **Dilarang Mencampur Logika UI dan 3D:** Jangan menuliskan kode kanvas Three.js langsung di dalam file halaman utama (`App.jsx`). Seluruh komponen 3D harus diisolasi di dalam folder `resources/js/three/`.
6. **Dilarang Menginstal Library UI Tambahan Tanpa Izin:** Jangan menambahkan Material UI, Bootstrap, atau Chakra UI karena proyek ini sudah menggunakan **Tailwind CSS** secara penuh.

# AGENT OPERATING GUIDELINES

Sebelum membuat, mengubah, atau menyarankan kode apa pun, Anda WAJIB memeriksa dan mematuhi dokumen acuan berikut:
1. **Fitur & Acceptance Criteria**: Periksa `PRD.md`.
2. **Skema Database MySQL & Struktur API**: Periksa `ARCHITECTURE.md` (jangan membuat kolom baru di luar skema tanpa persetujuan).
3. **Warna, Tipografi, & Styling**: Periksa `DESIGN.md` (gunakan kelas Tailwind yang sudah didefinisikan).
4. **Batasan & Konvensi Kode**: Patuhi aturan di bawah ini secara ketat.



---------------------------------------------------------------


<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.5. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-react-development` when working with Inertia client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== wayfinder/core rules ===

# Laravel Wayfinder

Use Wayfinder to generate TypeScript functions for Laravel routes. Import from `@/actions/` (controllers) or `@/routes/` (named routes).

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

=== inertia-react/core rules ===

# Inertia + React

- IMPORTANT: Activate `inertia-react-development` when working with Inertia React client-side patterns.

</laravel-boost-guidelines>
