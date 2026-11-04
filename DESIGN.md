# DESIGN.md — Sistem Desain & Panduan Antarmuka

Dokumen ini mendefinisikan identitas visual, palet warna, tipografi, tata letak, dan spesifikasi komponen UI agar menghasilkan tampilan _modern dark tech_ yang tajam, imersif, dan responsif seperti referensi `alfari.my.id`.

---

## 1. Color Palette (Palet Warna)

Desain menggunakan tema gelap (_Deep Space Dark Mode_) dengan aksen gradasi neon lembut dan efek kaca (_Glassmorphism_).

| Kategori                 | Nama Token              | Kode Hex                   | Penggunaan Utama                                                      |
| :----------------------- | :---------------------- | :------------------------- | :-------------------------------------------------------------------- |
| **Background Primary**   | `bg-space-950`          | `#0B0F17`                  | Latar belakang utama halaman (`<body>`)                               |
| **Background Secondary** | `bg-space-900`          | `#111827`                  | Latar belakang seksi alternatif, kartu proyek, dan input form         |
| **Surface Glass**        | `bg-glass`              | `rgba(17, 24, 39, 0.65)`   | Kartu kaca transparan dengan `backdrop-blur-md` dan border tipis      |
| **Text Primary**         | `text-slate-50`         | `#F8FAFC`                  | Judul utama (Heading), nama proyek, teks aktif                        |
| **Text Secondary**       | `text-slate-400`        | `#94A3B8`                  | Paragraf deskripsi, sub-judul, label form                             |
| **Accent Primary**       | `accent-indigo`         | `#6366F1`                  | Tombol utama (CTA), indikator tab aktif, sorotan teks penting         |
| **Accent Secondary**     | `accent-cyan`           | `#38BDF8`                  | Gradasi judul, efek _glow_ di belakang kanvas 3D, _badge_ teknologi   |
| **Border Subtle**        | `border-white/10`       | `rgba(255, 255, 255, 0.1)` | Garis tepi kartu, bingkai input, dan bingkai _navbar_                 |
| **Status Error/Warning** | `status-rose` / `amber` | `#F43F5E` / `#F59E0B`      | Peringatan gagal muat aset 3D & pesan validasi error pada form kontak |
| **Status Success**       | `status-emerald`        | `#10B981`                  | Notifikasi berhasil mengirim pesan pada formulir kontak               |

---

## 2. Typography (Tipografi)

Mengombinasikan tipografi tegas untuk judul, _sans-serif_ bersih untuk keterbacaan, dan _monospace_ untuk elemen teknis.

- **Heading Font (`font-display`):** `Space Grotesk` atau `Plus Jakarta Sans` (Bold / ExtraBold, `tracking-tight`).
- **Body Font (`font-sans`):** `Plus Jakarta Sans` atau `Inter` (Regular / Medium, `leading-relaxed`).
- **Monospace Font (`font-mono`):** `JetBrains Mono` (digunakan untuk angka persentase _preloader_ `70%`, _badge_ `React`, dan angka statistik).

### Skala Ukuran Teks (Desktop -> Mobile)

- **Hero Title (`H1`):** `3.5rem` ($56\text{px}$) pada desktop $\rightarrow$ `2.25rem` ($36\text{px}$) pada _mobile_, `font-extrabold`.
- **Section Heading (`H2`):** `2.25rem` ($36\text{px}$) pada desktop $\rightarrow$ `1.75rem` ($28\text{px}$) pada _mobile_, `font-bold`.
- **Card Title (`H3` / `H4`):** `1.25rem` ($20\text{px}$), `font-semibold`.
- **Body Text:** `1rem` ($16\text{px}$) untuk paragraf utama, `0.875rem` ($14\text{px}$) untuk teks sekunder.

---

## 3. Spacing, Layout & Z-Index

- **Container Utama:** `max-w-7xl` ($1280\text{px}$), berada di tengah (`mx-auto`) dengan _padding_ horizontal responsif (`px-4 sm:px-6 lg:px-8`).
- **Jarak Antar Seksi:** `py-20` ($80\text{px}$) pada _desktop_ dan `py-12` ($48\text{px}$) pada _mobile_.
- **Grid Sistem:**
    - **Seksi Hero & About:** Grid 2 kolom (`grid-cols-1 lg:grid-cols-12`, 7 kolom untuk teks dan 5 kolom untuk kanvas 3D ID Card).
    - **Daftar Proyek & Sertifikat:** Grid 3 kolom (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6`).
    - **Seksi Kontak (`#Contact`):** Grid 2 kolom proporsional (`grid-cols-1 lg:grid-cols-12 gap-8`), di mana 7 kolom untuk kartu **Formulir Hubungi Saya** dan 5 kolom untuk panel **Find Me (Sosial Media & Info Kontak)**.
- **Hirarki Z-Index:**
    - `z-50`: Layar _Preloader_ (`fixed inset-0`) & Modal Detail Proyek.
    - `z-40`: _Floating / Sticky Navbar_ dengan efek `backdrop-blur-md`.
    - `z-20`: Konten interaktif (Tombol, Kartu, Form).
    - `z-10`: Kanvas 3D Three.js.
    - `z-0`: Efek cahaya latar (_ambient radial gradient blobs_).

---

## 4. Spesifikasi Komponen UI

### 4.1. Layar Preloader Aset

- Layar penuh berwarna `#0B0F17` dengan angka persentase besar di tengah menggunakan `font-mono`.
- _Progress bar_ horizontal tipis dengan efek cahaya (`shadow-[0_0_15px_#6366F1]`).
- Jika ada aset gagal dimuat, tampilkan kotak peringatan kecil berwarna _amber/rose_ berisi teks jumlah aset gagal beserta dua tombol: **Coba Lagi** (solid indigo) dan **Lanjutkan** (outline border tipis).

### 4.2. Kartu Statistik & Riwayat (About Section)

- **Kartu Statistik (Projects, Certificates, Awards):** Menampilkan angka besar di sudut atas (`text-3xl font-bold text-white`), judul kategori, dan sub-teks deskripsi singkat (`"Karya yang dibuat"`, `"Skill yang divalidasi"`, `"Pencapaian diraih"`).
- **Timeline Pendidikan & Pengalaman:** Kartu vertikal dengan indikator titik (_dot_) dan garis samping, menampilkan nama institusi (`SMK / Universitas`), jurusan/posisi, serta badge rentang tahun (`2024 – 2027`).

### 4.3. Navigasi Tab Portofolio

- Berbentuk _pill bar_ di bagian atas seksi `#Portofolio` dengan 4 tombol: **Projects**, **Certificates**, **Achievements**, dan **Tech Stack**.
- Tab yang aktif memiliki latar gradasi `from-indigo-500/20 to-cyan-500/20`, teks putih terang, dan indikator garis bawah yang bergerak halus menggunakan `framer-motion` (`layoutId="activeTab"`).

### 4.4. Formulir Kontak & Panel "Find Me"

- **Kartu Formulir Kontak:** Menggunakan latar `bg-glass` dengan input **Nama**, **Email**, dan `textarea` **Pesan**. Setiap input memiliki efek fokus `focus:ring-2 focus:ring-indigo-500 focus:border-transparent`.
- **Tombol Kirim Pesan:** Lebar penuh (`w-full`) dengan gradasi `from-indigo-600 to-cyan-500`, menampilkan ikon pesawat kertas (Lucide `Send`) dan animasi _spinner_ saat proses pengiriman berlangsung.
- **Panel Find Me:** Menampilkan daftar kartu tautan sosial media (Instagram, TikTok, GitHub, LinkedIn) dengan ikon merek, _username_, dan efek _hover_ bergeser halus (`hover:translate-x-1 hover:border-indigo-500/40 transition-all`).

---

## 5. Responsive Design (Desain Responsif)

| Breakpoint                         | Lebar Layar                    | Penyesuaian Tata Letak                                                                                                                            |
| :--------------------------------- | :----------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Mobile (`default` / `< 640px`)** | $320\text{px} - 639\text{px}$  | Navigasi berubah menjadi _Hamburger Menu_. Kanvas 3D ID Card memiliki tinggi terukur (`h-[380px]`) agar tidak menghalangi _scroll_ jari pengguna. |
| **Tablet (`md: >= 768px`)**        | $768\text{px} - 1023\text{px}$ | Grid proyek menjadi 2 kolom. Tab portofolio ditampilkan sejajar penuh.                                                                            |
| **Desktop (`lg: >= 1024px`)**      | $\ge 1024\text{px}$            | Tata letak penuh 2 kolom untuk Hero/About (teks di kiri, 3D ID Card di kanan) serta Formulir Kontak berdampingan dengan panel Find Me.            |

### Perbaikan Khusus Spotify Embed di Mobile

Pada web referensi, pengunjung mengeluhkan bagian Spotify yang rusak saat dibuka di _smartphone_. Untuk mengatasinya:

- Bungkus `<iframe>` Spotify di dalam container `<div className="w-full max-w-full overflow-hidden rounded-2xl border border-white/10">`.
- Tetapkan atribut `width="100%"` dan `height="352"` pada `<iframe>`, serta pastikan _parent container_ tidak memiliki `min-width` kaku yang melebihi lebar layar _viewport_.
