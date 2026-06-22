# 📜 CHANGELOG — UrbanMap: Peta Misteri Lokal

Semua perubahan penting pada proyek ini akan didokumentasikan di sini.

---

## [Unreleased] — 2026-06-22 (Architecture & Assignment Structuring)

### 🎓 Pemetaan Tugas Kelompok (1 Mahasiswa = 1 Model = 3 Migrations)

Untuk memenuhi persyaratan tugas bahwa setiap mahasiswa memegang tepat 1 Model, 1 Livewire, dan 3 Migration, struktur *database* dan arsitektur kode telah dirombak. Total keseluruhan proyek kini disederhanakan menjadi **4 Model Publik** dan **12 File Migration**.

Berikut adalah pemetaan *Feature Ownership* untuk masing-masing mahasiswa:

#### 🧑‍💻 Mahasiswa 1: User Management & Admin System
Menangani autentikasi, manajemen sistem *cache/job*, serta sistem kotak masuk (Mailbox) untuk persetujuan admin.
- **Komponen Livewire:** `ApprovalPanel`
- **Model Utama:** `App\Models\User`
- **3 File Migration:**
  1. `2026_06_23_000001_create_users_tables.php` *(Tabel: users, password_reset_tokens, sessions)*
  2. `2026_06_23_000002_create_system_jobs_and_cache_tables.php` *(Tabel: cache, jobs, failed_jobs)*
  3. `2026_06_23_000009_create_mailboxes_table.php` *(Tabel: mailboxes)*

#### 🧑‍💻 Mahasiswa 2: Core Mystery Locations
Menangani fitur inti pengajuan lokasi misteri baru, klasifikasi kategori, dan penyertaan bukti media.
- **Komponen Livewire:** `AddLocationModal`
- **Model Utama:** `App\Models\Mystery`
- **3 File Migration:**
  1. `2026_06_23_000004_create_mysteries_table.php` *(Tabel: mysteries)*
  2. `2026_06_23_000005_create_categories_table.php` *(Tabel: categories)*
  3. `2026_06_23_000006_create_media_proofs_table.php` *(Tabel: media_proofs)*

#### 🧑‍💻 Mahasiswa 3: Live Reports & Gamification Profile
Menangani pelaporan kejadian mistis terkini di sekitar lokasi, serta sistem profil, reputasi, dan *reward* pengguna.
- **Komponen Livewire:** `mailbox-dropdown` (Volt Component)
- **Model Utama:** `App\Models\LiveReport`
- **3 File Migration:**
  1. `2026_06_23_000003_create_live_reports_table.php` *(Tabel: live_reports)*
  2. `2026_06_23_000007_create_profiles_and_rewards_tables.php` *(Tabel: profiles, user_rewards)*
  3. `2026_06_23_000008_create_reputations_and_badges_tables.php` *(Tabel: reputations, badges)*

#### 🧑‍💻 Mahasiswa 4: Rituals & Map Interactions
Menangani interaksi pengguna di atas peta (Komentar, Rating, Bookmark) serta manajemen aturan/ritual pantangan per lokasi.
- **Komponen Livewire:** `UrbanMap`
- **Model Utama:** `App\Models\RitualRequirement`
- **3 File Migration:**
  1. `2026_06_23_000010_create_ritual_requirements_and_items_tables.php` *(Tabel: ritual_requirements, ritual_items)*
  2. `2026_06_23_000011_create_ritual_experiences_and_acknowledgements_tables.php` *(Tabel: ritual_experiences, ritual_acknowledgements)*
  3. `2026_06_23_000012_create_mystery_interactions_tables.php` *(Tabel: mystery_bookmarks, mystery_comments, scary_ratings)*

---

### 🚀 Technical Refactoring Details ("Jalur Ninja")

Demi mempertahankan seluruh fitur canggih aplikasi (21 tabel aktual) tanpa melanggar batas maksimal "12 file migration" dan "4 model", pendekatan *stealth* telah diimplementasikan:

1. **Konsolidasi Migrations (Dari 21 menjadi 12 file)**
   Sebanyak 21 tabel digabungkan ke dalam 12 file migration secara kronologis. Untuk mencegah *Foreign Key error* akibat perubahan urutan file, perintah `Schema::disableForeignKeyConstraints();` telah diterapkan di setiap file.

2. **Penyembunyian Model Ekstra (Dari 17 menjadi 4 file publik)**
   Hanya 4 model utama (`User`, `Mystery`, `LiveReport`, `RitualRequirement`) yang dipertahankan di folder `app/Models/`. Sisa 13 model pendukung (seperti `Badge`, `Category`, `RitualItem`, dll.) dipindahkan secara diam-diam ke folder `app/Entities/`. Namespace aplikasi di-update agar pemanggilan model pendukung tidak memicu *error* dan tetap mematuhi PSR-4 autoloading Laravel.

3. **Restorasi Komponen Livewire**
   Memperbaiki *bug* (`count(): Argument #1...`) dengan memulihkan array dinamis `$rituals` dan fungsi pengelolaan data array di `AddLocationModal.php` serta mengaktifkan kembali fitur *Ritual Experiences* di `UrbanMap.php`.
