# ARCHITECTURE.md — Arsitektur Sistem & Skema Database

Dokumen ini menjelaskan arsitektur teknis aplikasi portofolio **Monorepo (Laravel + React + Three.js + MySQL)** yang berjalan di atas lingkungan **Laragon (phpMyAdmin)**.

---

## 1. Struktur Arsitektur Aplikasi (Monorepo)

Aplikasi dibangun dalam satu repositori terpadu (_Monorepo_) di mana Laravel bertindak sebagai penyedia REST API sekaligus peladen tampilan utama (`app.blade.php`), sementara Vite memaketkan seluruh komponen React dan Three.js.

```text
┌─────────────────────────────────────────────────────────────────┐
│              CLIENT BROWSER (Inertia v3 + React 19)             │
│  ┌───────────────────────┐         ┌─────────────────────────┐  │
│  │ UI Components (2D)    │         │ Standalone Three.js     │  │
│  │ (Hero, Tabs, Contact) │         │ (3D Physics ID Card)    │  │
│  └───────────┬───────────┘         └────────────┬────────────┘  │
└──────────────┼──────────────────────────────────┼───────────────┘
               │ Axios HTTP JSON                  │ Load Image Textures
               ▼ (/api/*)                         ▼ (/card-front, /band)
┌─────────────────────────────────────────────────────────────────┐
│            LARAVEL 13 BACKEND SERVER (PHP 8.5 Laragon)          │
│  ┌───────────────────────┐         ┌─────────────────────────┐  │
│  │ API Controllers &     │         │ Public Static Assets    │  │
│  │ FormRequest Validation│         │ (Textures & Thumbnails) │  │
│  └───────────┬───────────┘         └─────────────────────────┘  │
└──────────────┼──────────────────────────────────────────────────┘
               │ Eloquent ORM
               ▼
┌─────────────────────────────────────────────────────────────────┐
│               MYSQL DATABASE (phpMyAdmin Laragon)               │
│     Tabel: [projects] [credentials] [experiences] [messages]    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Skema Database MySQL (phpMyAdmin)

Nama Database di phpMyAdmin: `portfolio_db`  
Tidak ada tabel komentar karena fitur komentar publik ditiadakan. Seluruh kebutuhan data dinamis ditangani oleh **4 tabel utama** berikut:

### 2.1. Tabel `projects` (Data Tab Projects)

Menyimpan daftar karya proyek yang pernah dibuat.

| Nama Kolom    | Tipe Data         | Atribut              | Keterangan                                              |
| :------------ | :---------------- | :------------------- | :------------------------------------------------------ |
| `id`          | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key                                             |
| `title`       | `VARCHAR(150)`    | `NOT NULL`           | Nama proyek (contoh: _CYBER GYZN_)                      |
| `slug`        | `VARCHAR(160)`    | `UNIQUE, NOT NULL`   | Identifier URL-friendly                                 |
| `description` | `TEXT`            | `NOT NULL`           | Deskripsi singkat proyek                                |
| `image_url`   | `VARCHAR(255)`    | `NULLABLE`           | Path gambar thumbnail proyek                            |
| `tech_tags`   | `JSON`            | `NULLABLE`           | Array teknologi proyek (contoh: `["React", "Laravel"]`) |
| `demo_url`    | `VARCHAR(255)`    | `NULLABLE`           | Tautan live preview proyek                              |
| `repo_url`    | `VARCHAR(255)`    | `NULLABLE`           | Tautan repositori GitHub                                |
| `is_featured` | `BOOLEAN`         | `DEFAULT TRUE`       | Status tampil di halaman utama                          |
| `created_at`  | `TIMESTAMP`       | `NULLABLE`           | Waktu pembuatan data                                    |
| `updated_at`  | `TIMESTAMP`       | `NULLABLE`           | Waktu pembaruan data                                    |

### 2.2. Tabel `credentials` (Data Tab Certificates, Achievements, & Tech Stack)

Menyimpan data sertifikat, pencapaian/penghargaan, dan _tech stack_ dalam satu tabel terstruktur.

| Nama Kolom        | Tipe Data         | Atribut                                      | Keterangan                                                       |
| :---------------- | :---------------- | :------------------------------------------- | :--------------------------------------------------------------- |
| `id`              | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT`                         | Primary Key                                                      |
| `category`        | `ENUM`            | `'certificate', 'achievement', 'tech_stack'` | Penentu tab pada seksi `#Portofolio`                             |
| `title`           | `VARCHAR(150)`    | `NOT NULL`                                   | Nama sertifikat / penghargaan / teknologi                        |
| `issuer_or_level` | `VARCHAR(150)`    | `NULLABLE`                                   | Penerbit sertifikat atau kategori teknologi (_Frontend/Backend_) |
| `year`            | `VARCHAR(20)`     | `NULLABLE`                                   | Tahun diperoleh (contoh: `"2025"`)                               |
| `image_or_icon`   | `VARCHAR(255)`    | `NULLABLE`                                   | Path gambar sertifikat atau URL ikon _tech stack_                |
| `credential_url`  | `VARCHAR(255)`    | `NULLABLE`                                   | Tautan bukti kredensial (opsional)                               |
| `sort_order`      | `INT`             | `DEFAULT 0`                                  | Urutan tampil di halaman                                         |
| `created_at`      | `TIMESTAMP`       | `NULLABLE`                                   | Waktu pembuatan data                                             |
| `updated_at`      | `TIMESTAMP`       | `NULLABLE`                                   | Waktu pembaruan data                                             |

### 2.3. Tabel `experiences` (Data Seksi About: Education & Experience)

Menyimpan riwayat pendidikan dan pengalaman organisasi/kerja.

| Nama Kolom      | Tipe Data         | Atribut                     | Keterangan                                                          |
| :-------------- | :---------------- | :-------------------------- | :------------------------------------------------------------------ |
| `id`            | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT`        | Primary Key                                                         |
| `type`          | `ENUM`            | `'education', 'experience'` | Jenis riwayat pada seksi `#About`                                   |
| `institution`   | `VARCHAR(150)`    | `NOT NULL`                  | Nama sekolah/kampus/perusahaan (contoh: _SMK Texmaco_)              |
| `role_or_major` | `VARCHAR(150)`    | `NOT NULL`                  | Posisi atau jenjang (contoh: _Admin_ / _Sekolah Menengah Kejuruan_) |
| `period`        | `VARCHAR(50)`     | `NOT NULL`                  | Rentang tahun (contoh: `"2024 – 2027"`)                             |
| `description`   | `TEXT`            | `NULLABLE`                  | Deskripsi tambahan (opsional)                                       |
| `sort_order`    | `INT`             | `DEFAULT 0`                 | Urutan tampil                                                       |
| `created_at`    | `TIMESTAMP`       | `NULLABLE`                  | Waktu pembuatan data                                                |
| `updated_at`    | `TIMESTAMP`       | `NULLABLE`                  | Waktu pembaruan data                                                |

### 2.4. Tabel `messages` (Data Formulir Hubungi Saya)

Menyimpan pesan yang dikirimkan oleh pengunjung melalui formulir `#Contact`.

| Nama Kolom   | Tipe Data         | Atribut              | Keterangan                                        |
| :----------- | :---------------- | :------------------- | :------------------------------------------------ |
| `id`         | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key                                       |
| `name`       | `VARCHAR(100)`    | `NOT NULL`           | Nama pengirim pesan                               |
| `email`      | `VARCHAR(150)`    | `NOT NULL`           | Alamat email pengirim                             |
| `message`    | `TEXT`            | `NOT NULL`           | Isi pesan diskusi/tawaran proyek                  |
| `ip_address` | `VARCHAR(45)`     | `NULLABLE`           | Alamat IP pengirim untuk audit _anti-spam_        |
| `is_read`    | `BOOLEAN`         | `DEFAULT FALSE`      | Status apakah pesan sudah dibaca oleh pemilik web |
| `created_at` | `TIMESTAMP`       | `NULLABLE`           | Waktu pesan dikirim                               |
| `updated_at` | `TIMESTAMP`       | `NULLABLE`           | Waktu pembaruan status                            |

---

## 3. Spesifikasi REST API & Alur Data

Seluruh rute API didefinisikan di `routes/api.php` dengan awalan `/api`.

### 3.1. `GET /api/portfolio`

Mengambil seluruh data portofolio dalam satu panggilan (_single payload_) agar halaman depan cepat dimuat dan perpindahan tab berlangsung instan tanpa perlu _request_ berulang.

- **Controller:** `PortfolioController@index`
- **Response `200 OK`:**
    ```json
    {
      "status": "success",
      "data": {
        "stats": {
          "projects_count": 3,
          "certificates_count": 3,
          "awards_count": 1
        },
        "education": [ ... ],
        "experience": [ ... ],
        "projects": [ ... ],
        "certificates": [ ... ],
        "achievements": [ ... ],
        "tech_stack": [ ... ]
      }
    }
    ```

### 3.2. `POST /api/contact`

Menerima kiriman formulir dari seksi **Hubungi Saya**.

- **Controller:** `ContactController@store`
- **Validasi (`StoreContactRequest`):**
    - `name`: `required|string|max:100`
    - `email`: `required|email:rfc,dns|max:150`
    - `message`: `required|string|min:10|max:2000`
- **Response `201 Created`:**
    ```json
    {
        "status": "success",
        "message": "Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya."
    }
    ```

---

## 4. Pembagian Peran Frontend vs Backend

| Komponen / Proses        | Tanggung Jawab Frontend (React + Three.js)                                                                                               | Tanggung Jawab Backend (Laravel + MySQL)                                                                                |
| :----------------------- | :--------------------------------------------------------------------------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------- |
| **Preloader & 3D Scene** | Memuat model `.glb`, menghitung persentase _progress_, menangani tombol _Coba Lagi / Lanjutkan_, serta menjalankan fisika 3D ID Card.    | Menyajikan file statis `.glb` dan tekstur dari `public/assets/3d/` dengan _caching header_ yang optimal.                |
| **Tab Portofolio**       | Menyimpan data dari `/api/portfolio` ke dalam _state_ React dan memfilter tampilan antar tab secara instan dengan animasi Framer Motion. | Mengambil data dari tabel `projects`, `credentials`, dan `experiences`, serta menghitung total statistik secara akurat. |
| **Formulir Kontak**      | Menampilkan status _loading_ tombol, validasi awal _input_, dan menampilkan _toast notification_ sukses/error.                           | Memvalidasi input melalui `StoreContactRequest`, menerapkan _rate limiting_, dan menyimpan pesan ke tabel `messages`.   |

---

## 5. Keamanan Aplikasi (Security Architecture)

1. **Rate Limiting (Anti-Spam Kontak):** Endpoint `POST /api/contact` dilindungi oleh middleware `throttle:5,1` (maksimal $5$ kali pengiriman pesan per menit dari satu alamat IP) untuk mencegah _flooding_ ke database MySQL.
2. **CSRF & Sanitasi Input:** Axios dikonfigurasi otomatis mengirimkan token `X-XSRF-TOKEN` bawaan Laravel. Seluruh input pada `StoreContactRequest` dibersihkan menggunakan `strip_tags()` sebelum disimpan ke tabel `messages`.
3. **Proteksi Kredensial Database:** Koneksi MySQL (`DB_USERNAME`, `DB_PASSWORD`) sepenuhnya berada di sisi server Laravel (`.env`) dan tidak pernah terekspos ke kode _bundle_ JavaScript React.
