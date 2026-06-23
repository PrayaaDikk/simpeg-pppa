# Database Schema & Eloquent Blueprint (SIMPEG PPPA)

This document serves as the absolute truth for the SQLite database structure. Refer to this document before constructing migrations, Eloquent models, factories, or query builders.

---

## 1. Core Authentication & System Tables

### `users` Table

Stores authentication credentials.

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`name`**: `string`
- **`email`**: `string` (Unique)
- **`email_verified_at`**: `timestamp` (Nullable)
- **`password`**: `string`
- **`remember_token`**: `string` (Nullable)
- **`timestamps`**: `created_at` & `updated_at`
- _Relationships_: `hasOne(Pegawai)`

### `sessions` Table

Handles stateful user sessions.

- **`id`**: `string` (Primary Key)
- **`user_id`**: `foreignId` -> `users.id` (Nullable, Indexed)
- **`ip_address`**: `string(45)` (Nullable)
- **`user_agent`**: `text` (Nullable)
- **`payload`**: `longText`
- **`last_activity`**: `integer` (Indexed)

---

## 2. Master Reference Data Tables

### `pangkat` Table

Defines civil service ranks and classes (Golongan).

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`nama_pangkat`**: `string` (e.g., Penata Muda, Pembina)
- **`golongan`**: `string(10)` (e.g., III/a, IV/b)
- **`timestamps`**: Standard tracking
- _Relationships_: `hasMany(Pegawai)`, `hasMany(RiwayatPangkat)`

### `bidang` Table

Defines structural sectors or divisions within the institution.

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`nama_bidang`**: `string`
- **`akronim`**: `string(50)`
- **`timestamps`**: Standard tracking
- _Relationships_: `hasMany(Pegawai)`

### `jabatan` Table

Defines standard structural or functional job titles.

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`nama_jabatan`**: `string`
- **`is_singleton`**: `boolean` (Default: `false` - indicates if only 1 employee can occupy this position, e.g., Head of Agency)
- **`timestamps`**: Standard tracking
- _Relationships_: `hasMany(Pegawai)`

---

## 3. Core Employee Management

### `pegawai` Table

The central profile entity containing comprehensive personal and professional metadata.

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`user_id`**: `foreignId` -> `users.id` (`cascadeOnDelete`, `cascadeOnUpdate`)
- **`bidang_id`**: `foreignId` -> `bidang.id` (Nullable, `nullOnDelete`)
- **`jabatan_id`**: `foreignId` -> `jabatan.id` (Nullable, `nullOnDelete`)
- **`pangkat_id`**: `foreignId` -> `pangkat.id` (Restricted)
- **`nip`**: `string` (Unique) - National Employee Number
- **`nama`**: `string`
- **`karpeg`**: `string(20)` (Nullable) - Civil Service Card Number
- **`jenis_kelamin`**: `enum('l', 'p')` - (l = Laki-laki, p = Perempuan)
- **`agama`**: `enum('islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu')` (Default: `'islam'`)
- **`tempat_lahir`**: `string`
- **`tanggal_lahir`**: `date`
- **`no_telp`**: `string`
- **`kode_pos`**: `string`
- **`alamat`**: `text`
- **`status_kawin`**: `enum('belum kawin', 'kawin', 'cerai hidup', 'cerai mati')` (Default: `'belum kawin'`)
- **`nama_pasangan`**: `string` (Nullable)
- **`status_kerja_pasangan`**: `string` (Nullable)
- **`jumlah_anak`**: `integer` (Nullable)
- **`jenis_pegawai`**: `enum('pns', 'cpns', 'pppk')` (Default: `'pns'`)
- **`tmt_pegawai`**: `date` - Terhitung Mulai Tanggal (Employment Effective Date)
- **`is_active`**: `boolean` (Default: `true`)
- **`foto`**: `string` (Nullable) - Path to profile image
- **`timestamps`**: Standard tracking
- _Relationships_:
- `belongsTo(User)`, `belongsTo(Bidang)`, `belongsTo(Jabatan)`, `belongsTo(Pangkat)`
- `hasMany(RiwayatPendidikan)`, `hasMany(RiwayatPangkat)`, `hasMany(RiwayatJabatan)`, `hasMany(Cuti)`, `hasMany(Kgb)`

---

## 4. History Tracking Tables (Log Riwayat)

### `riwayat_pendidikan` Table

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`pegawai_id`**: `foreignId` -> `pegawai.id` (`cascadeOnDelete`, `cascadeOnUpdate`)
- **`tingkat`**: `enum('SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3')`
- **`jurusan`**: `string`
- **`institusi`**: `string`
- **`tahun_lulus`**: `year`
- **`tingkat_pendidikan`**: `integer` (Default: `0`, Indexed for chronological sorting logic)
- **`ijazah`**: `string` (Nullable) - File path reference
- **`timestamps`**: Standard tracking

### `riwayat_pangkat` Table

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`pegawai_id`**: `foreignId` -> `pegawai.id` (`cascadeOnDelete`, `cascadeOnUpdate`)
- **`pangkat_id`**: `foreignId` -> `pangkat.id` (Nullable, `nullOnDelete`)
- **`tmt_pangkat`**: `date` - Rank Effective Date
- **`nomor_sk`**: `string` - Decree Document Number
- **`file_sk`**: `string` (Nullable) - File path reference
- **`timestamps`**: Standard tracking

### `riwayat_jabatan` Table

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`pegawai_id`**: `foreignId` -> `pegawai.id` (`cascadeOnDelete`)
- **`nama_jabatan`**: `string` (Snapshot of job title at assignment time)
- **`tmt_jabatan`**: `date`
- **`nomor_sk`**: `string`
- **`tanggal_sk`**: `date`
- **`timestamps`**: Standard tracking

---

## 5. Operational Functional Modules

### `cuti` Table (Leave Requests)

Tracks leave submissions, approvals, and workplace tracking details.

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`pegawai_id`**: `foreignId` -> `pegawai.id` (`cascadeOnDelete`)
- **`atasan_id`**: `foreignId` -> `pegawai.id` (Nullable, `nullOnDelete` - references supervisor reviewing the request)
- **`jenis_cuti`**: `enum('tahunan', 'besar', 'sakit', 'melahirkan', 'alasan penting', 'diluar tanggungan negara')`
- **`alasan_cuti`**: `text` (Nullable)
- **`tanggal_mulai`**: `date`
- **`tanggal_akhir`**: `date`
- **`lama_cuti`**: `integer` (Duration calculated in days)
- **`alamat`**: `text` (Contact address while on leave)
- **`no_telp`**: `string(15)`
- **`catatan_cuti`**: `text` (Nullable)
- **`keputusan_atasan`**: `enum('menunggu', 'disetujui', 'perubahan', 'ditangguhkan', 'tidak disetujui')` (Default: `'menunggu'`)
- **`status_cuti`**: `enum('menunggu', 'disetujui', 'perubahan', 'ditangguhkan', 'tidak disetujui')` (Default: `'menunggu'`)
- **`timestamps`**: Standard tracking

### `kgb` Table (Periodic Salary Increment)

Handles tracking for the periodic salary progression data ledger.

- **`id`**: `bigint` (Primary Key, Auto Increment)
- **`pegawai_id`**: `foreignId` -> `pegawai.id` (`cascadeOnDelete`)
- **`golongan_lama`**: `string`
- **`gaji_lama`**: `integer`
- **`masa_kerja_lama`**: `string`
- **`tmt_gaji_lama`**: `date`
- **`golongan_baru`**: `string`
- **`gaji_baru`**: `integer`
- **`masa_kerja_baru`**: `string`
- **`tmt_gaji_baru`**: `date`
- **`kgb_berikutnya`**: `date` (Determines deadline timeline for next progression review)
- **`status_kgb`**: `enum('menunggu', 'disetujui', 'tidak disetujui')` (Default: `'menunggu'`)
- **`timestamps`**: Standard tracking

---

## 6. AI Development Rules For Database Context & Auth Automation

1. **Implicit Conversions**: When displaying currencies or financial records like `gaji_lama` and `gaji_baru`, make sure formatting uses Indonesian Rupiah (`IDR`) on the UI, but store as raw `integer` on the database level.
2. **Singular/Plural Table Name Warning**: Note that master tables use singular nouns (`pangkat`, `bidang`, `jabatan`, `pegawai`, `cuti`, `kgb`), while framework default tables use plural nouns (`users`, `sessions`). Do NOT attach accidental plural 's' modifiers to internal application queries.
3. **Foreign Keys Constraint**: Ensure data updates enforce cascade rules appropriately. `pegawai` deletions must automatically flush cascade logs (`riwayat_*`, `cuti`, `kgb`).
4. **Automated Employee Creation (CRITICAL)**:
    - When an Admin creates a new `pegawai`, the backend MUST automatically create a corresponding `users` record first.
    - **Email Generation:** Set `users.email` automatically using the format: `[NIP]@simpeg.local` (since Fortify requires an email format for authentication).
    - **Default Password:** Set `users.password` using a hashed combination format: `p3a[NIP]`.
    - **First-Time Login Detection:** Keep `users.email_verified_at` as `NULL` upon creation. This `NULL` state indicates a first-time login, prompting the frontend to force a password change.
