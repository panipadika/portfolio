# Product Requirements Document (PRD)

**Nama Proyek:** Personal Portfolio Full-Stack Developer (3D Interactive Web)  
**Arsitektur:** Monorepo (Laravel + React + Three.js + MySQL)  
**Referensi Utama:** `alfari.my.id` (Tanpa Fitur Komentar Publik)

---

## 1. Problem Statement (Pernyataan Masalah)

Sebagai seorang Web / Full-Stack Developer, portofolio statis biasa tidak lagi cukup untuk membuktikan kapabilitas teknis secara menyeluruh. Pengunjung dan _recruiter_ membutuhkan bukti nyata kemampuan _front-end_ modern (interaksi 3D, animasi halus, dan antarmuka responsif) sekaligus keandalan _back-end_ (manajemen database relasional di MySQL, penyajian REST API yang cepat, dan keamanan formulir kontak).

Selain itu, berdasarkan analisis pada web referensi, terdapat beberapa kendala teknis yang harus diselesaikan pada proyek ini:

1. **Kegagalan Pemuatan Aset 3D:** Pengguna membutuhkan indikator progres pemuatan aset (_preloader_) yang transparan disertai opsi _retry_ atau _skip_ jika koneksi lambat.
2. **Responsivitas Komponen Pihak Ketiga:** Komponen _embed_ Spotify sering merusak tata letak (_overflow_) ketika dibuka di layar perangkat _mobile_.
3. **Fokus Komunikasi Profesional:** Menghilangkan kolom komentar publik yang rawan _spam_ atau kata-kata tidak pantas, dan memfokuskan seluruh interaksi pengunjung melalui **Formulir Kontak ("Hubungi Saya")** yang bersih serta tautan sosial media resmi (**"Find Me"**).

---

## 2. Target Pengguna (Target Audience)

1. **Tech Recruiter & HRD:** Menilai kualifikasi teknis, riwayat pendidikan, pengalaman kerja, sertifikasi, dan kualitas kode melalui karya yang ditampilkan.
2. **Klien Potensial (Freelance/Project Owner):** Mencari referensi hasil kerja nyata (_Projects_) dan menghubungi _developer_ secara langsung melalui formulir kontak.
3. **Sesama Developer & Komunitas IT:** Mengeksplorasi interaksi 3D (seperti _ID Card_ 3D yang dapat ditarik), melihat _tech stack_, dan terhubung melalui GitHub atau sosial media.

---

## 3. Fitur Utama (Core Features)

### 3.1. Preloader Aset 3D Interaktif

- Menampilkan persentase pemuatan aset (`0%` hingga `100%`).
- Mendeteksi jumlah aset yang gagal dimuat (contoh: _"3 aset gagal dimuat"_).
- Menyediakan tombol aksi **Coba Lagi** (memuat ulang aset yang gagal) dan **Lanjutkan** (masuk ke halaman utama dengan _fallback_ meskipun aset 3D belum lengkap).

### 3.2. Hero & Interactive 3D Section (`#Home` & `#About`)

- **Headline & Intro:** Kalimat perkenalan singkat, _badge_ teknologi utama (`React`), serta tombol pintasan **Lihat Projek** (`#Portofolio`) dan **Hubungi Saya** (`#Contact`).
- **3D Draggable ID Card (Lanyard):** Komponen kanvas Three.js interaktif yang menampilkan kartu identitas 3D yang dapat ditarik (_drag & drop_ dengan efek fisika pegas/gravitasi).
- **Profil, Pendidikan & Pengalaman:** Menampilkan biografi singkat, riwayat pendidikan (_Education_) beserta tahunnya, pengalaman (_Experience_), serta kartu statistik dinamis untuk jumlah **Projects**, **Certificates**, dan **Awards**.

### 3.3. Seksi Musik Spotify (`Daily Rotation`)

- Menyematkan _Spotify Embed Player_ untuk menampilkan _playlist_ musik pengiring _coding_ (seperti _NgodingTime_).
- Dilengkapi pembungkus (_wrapper_) responsif agar tampilan tidak rusak pada layar _smartphone_.

### 3.4. Showcase Portofolio Multi-Tab (`#Portofolio`)

Terdiri dari 4 tab navigasi dinamis yang datanya diambil dari database MySQL:

1. **Projects:** Menampilkan kartu proyek berisi gambar pratinjau, judul, deskripsi singkat, dan tombol **Details** (menuju modal detail atau tautan proyek).
2. **Certificates:** Menampilkan daftar sertifikat keahlian yang telah divalidasi.
3. **Achievements:** Menampilkan daftar penghargaan atau pencapaian kompetisi.
4. **Tech Stack:** Menampilkan ikon dan nama teknologi (_front-end_, _back-end_, _database_, _tools_) yang dikuasai.

### 3.5. Formulir Kontak & Sosial Media (`#Contact`)

- Formulir pengiriman pesan langsung berisi input **Nama**, **Email**, dan **Pesan**.
- Menyimpan pesan langsung ke tabel `messages` di MySQL dan menampilkan notifikasi sukses/gagal.
- Menampilkan panel **Find Me** berisi tautan sosial media (GitHub, Instagram, TikTok, LinkedIn).

---

## 4. User Flow (Alur Pengguna)

```text
[Pengunjung Membuka Website]
         │
         ▼
[Layar Preloader Aset (0% - 100%)]
   ├── (Semua Aset Berhasil) ─────────────────────────┐
   └── (Ada Aset Gagal) -> [Klik "Coba Lagi" / "Lanjutkan"]
                                                      │
         ┌────────────────────────────────────────────┘
         ▼
[Halaman Utama (#Home)]
   ├── Melihat Intro & Tautan Sosial Media
   └── Berinteraksi menarik 3D ID Card
         │
         ▼
[Seksi Tentang Saya (#About) & Musik Spotify]
   ├── Membaca Riwayat Pendidikan, Pengalaman & Statistik Karya
   └── Memutar pratinjau lagu di Spotify Embed Player
         │
         ▼
[Seksi Portofolio (#Portofolio)]
   └── Berpindah antar Tab: [Projects] <-> [Certificates] <-> [Achievements] <-> [Tech Stack]
         │
         ▼
[Seksi Kontak (#Contact)]
   ├── Mengisi Form "Hubungi Saya" (Nama, Email, Pesan) -> [Validasi Laravel] -> [Tersimpan di MySQL]
   └── Mengunjungi Sosial Media melalui kartu "Find Me" (GitHub / Instagram / TikTok)
```

---

## 5. Acceptance Criteria (Kriteria Penerimaan)

| ID        | Fitur                 | Kriteria Penerimaan (Acceptance Criteria)                                                                                                                                                                              |
| :-------- | :-------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **AC-01** | **Preloader 3D**      | Persentase _loading_ bertambah secara akurat. Jika terjadi _error_ pemuatan tekstur/aset 3D atau waktu pemuatan melebihi batas waktu (timeout 6 detik), tombol "Coba Lagi" dan "Lanjutkan" wajib muncul dan berfungsi. |
| **AC-02** | **3D ID Card**        | Kanvas 3D berjalan minimal $30\text{ FPS}$ di perangkat desktop dan dapat ditarik (_draggable_) menggunakan kursor maupun sentuhan layar tanpa menyebabkan _memory leak_.                                              |
| **AC-03** | **Tab Portofolio**    | Perpindahan antara tab _Projects_, _Certificates_, _Achievements_, dan _Tech Stack_ berlangsung instan tanpa memuat ulang seluruh halaman (_client-side state_).                                                       |
| **AC-04** | **Spotify Responsif** | Komponen _iframe_ Spotify tidak menyebabkan _horizontal scroll_ atau memotong tata letak pada layar dengan lebar $320\text{px}$ hingga $480\text{px}$.                                                                 |
| **AC-05** | **Formulir Kontak**   | Input _Nama_, _Email_ (format valid), dan _Pesan_ wajib diisi. Setelah dikirim, tombol berubah menjadi status _loading_, menyimpan data ke tabel `messages` di MySQL, dan mengosongkan form ketika berhasil.           |
