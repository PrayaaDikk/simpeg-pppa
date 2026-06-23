# 🚀 Panduan Clone & Instalasi Proyek (Laravel + React Inertia + MySQL)

Panduan ini memuat langkah-langkah terstruktur untuk menduplikasi (_clone_), memasang dependensi, mengonfigurasi basis data MySQL, hingga menjalankan aplikasi di komputer lokal Anda.

---

## 🛠️ Langkah Demi Langkah Instalasi

### 1. Clone Repositori

Buka terminal atau command prompt Anda, lalu jalankan perintah berikut untuk mengunduh proyek:

```bash
git clone <https://github.com/prayaadikk/simpeg-pppa.git> simpeg-pppa

```

Masuk ke dalam direktori proyek:

```bash
cd simpeg-pppa

```

### 2. Pasang Dependensi Backend (Composer)

Unduh dan pasang semua pustaka serta framework PHP yang dibutuhkan melalui Composer:

```bash
composer install

```

### 3. Pasang Dependensi Frontend (NPM)

Unduh paket-paket JavaScript (React, Lucide React, Shadcn dependencies, Vite) yang diperlukan oleh frontend:

```bash
npm install

```

### 4. Konfigurasi Environment (`.env`)

Salin berkas konfigurasi default `.env.example` menjadi berkas `.env` aktif:

```bash
cp .env.example .env

```

### 5. Konfigurasi Basis Data MySQL

Buka aplikasi manajemen database Anda (seperti phpMyAdmin, TablePlus, atau DBeaver) lalu **buat sebuah database baru** dengan nama bebas, misalnya: `db_kepegawaian`.

Setelah itu, buka berkas `.env` menggunakan teks editor Anda, kemudian sesuaikan bagian konfigurasi database menggunakan **MySQL**:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_kepegawaian
DB_USERNAME=root
DB_PASSWORD=

```

### 6. Generate Aplikasi Key

Buat _secure key_ unik untuk enkripsi session dan cookie aplikasi Anda:

```bash
php artisan key:generate

```

### 7. Migrasi & Seed Data Basis Data

Jalankan migrasi tabel skema database beserta data awal (_seeders_) ke dalam MySQL:

```bash
php artisan migrate --seed

```

### 8. Hubungkan Storage Link

Karena aplikasi ini mengelola berkas unggahan fisik (seperti dokumen berkas Ijazah, file SK Pangkat, dll.), buatlah direktori simetris (simbolik) agar dapat diakses oleh publik:

```bash
php artisan storage:link

```

---

## ⚡ Menjalankan Aplikasi

Untuk melihat aplikasi berjalan di peramban web (_browser_), Anda perlu menyalakan **dua server** secara bersamaan di terminal yang berbeda.

### Terminal 1: Jalankan Server Kendali Backend (Laravel)

```bash
php artisan serve

```

Aplikasi backend Anda akan aktif secara default di alamat: `http://127.0.0.1:8000`

### Terminal 2: Jalankan Compiler Aset Frontend (Vite)

```bash
npm run dev

```

Vite akan mengompilasi komponen React, Tailwind CSS, serta melacak perubahan kode secara _real-time_ (Hot Module Replacement).

Sekaruh, buka browser Anda dan akses tautan **`http://127.0.0.1:8000`**.

---

## 🗃️ Perintah Tambahan yang Berguna

- **Reset Ulang Database:** Jika ingin mengosongkan database MySQL dan mengulang migrasi dari awal:

```bash
php artisan migrate:fresh --seed

```

- **Membersihkan Cache Aplikasi:** Jika terjadi kendala pembacaan konfigurasi setelah mengubah `.env`:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear

```

- **Build Frontend untuk Produksi:** Sebelum melakukan _deploy_ ke server asli (hosting/VPS):

```bash
npm run build

```
