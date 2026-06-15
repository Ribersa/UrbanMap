<div align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1200px-Laravel.svg.png" alt="Laravel Logo" width="100"/>
  <h1>👻 UrbanMap - Peta Misteri Lokal</h1>
  <p>
    <strong>Platform berbasis komunitas untuk memetakan kisah mistis, sejarah wingit, dan mitos urban di sekitarmu.</strong>
  </p>
  <p>
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine JS" />
    <img src="https://img.shields.io/badge/Mapbox-000000?style=for-the-badge&logo=mapbox&logoColor=white" alt="Mapbox" />
  </p>
</div>

---

## 📖 Tentang Proyek

**UrbanMap** (Peta Misteri Lokal) adalah aplikasi _web_ pemetaan kolaboratif yang menggabungkan fitur peta interaktif dengan komunitas berbagi cerita lokal. Pengguna dapat mengeksplorasi titik-titik lokasi yang memiliki latar belakang sejarah, mitos, kutukan, hingga kisah mistis yang beredar di masyarakat sekitar.

Proyek ini dibangun di atas _stack_ modern **TALL** (Tailwind, Alpine, Laravel, Livewire) dan ditenagai oleh **Mapbox GL JS** untuk _rendering_ peta berkinerja tinggi.

## ✨ Fitur Utama

- 🗺️ **Peta Interaktif Dinamis**: Navigasi lancar dengan berbagai _custom map style_ (Dark, Light, Satellite, Streets, Outdoors, Navigation Night).
- 👻 **Kategori Misteri**: Indikator warna dinamis untuk setiap jenis misteri:
  - 🔴 Penampakan Makhluk Halus
  - 🟣 Tempat Bersejarah / Wingit
  - 🟡 Mitos Hewan / Pesugihan
  - 🟢 Kutukan / Tempat Sakral
- 📡 **Radar Spooky**: Memanfaatkan HTML5 Geolocation API untuk mendeteksi lokasi mistis dalam radius terdekat dari posisimu saat ini, lengkap dengan animasi radar berdenyut.
- 📍 **Auto Fly-To**: Peta otomatis membidik (_cinematic fly-to_) lokasi _real-time_ kamu menggunakan sensor GPS/Lokasi perangkat.
- ✍️ **Kontribusi Komunitas**: _User_ dapat mendaftar, _login_ menggunakan (Laravel Fortify), dan menambahkan titik misteri baru beserta rincian cerita & tingkat keseramannya (Scary Level).
- 🛡️ **Admin Panel**: Sistem moderasi bagi Admin untuk menyetujui (_Approve_) atau menolak (_Reject_) laporan lokasi baru dari komunitas.
- 🎓 **Onboarding Tour**: Tutorial interaktif langkah demi langkah dengan *Highlighter Overlay* yang menyorot fitur penting bagi _user_ baru secara otomatis.

## 🛠️ Persyaratan Sistem

- **PHP**: ^8.2
- **Composer**: Versi terbaru
- **Node.js & NPM**: Untuk mengompilasi aset _frontend_ (Tailwind)
- **Database**: MySQL / MariaDB / SQLite
- **Mapbox API Key**: Silakan daftar di [Mapbox](https://www.mapbox.com/) untuk mendapatkan _Access Token_.

## 🚀 Cara Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek secara lokal:

1. **Clone Repositori**
   ```bash
   git clone https://github.com/Ribersa/UrbanMap.git
   cd UrbanMap
   ```

2. **Install Dependensi PHP & Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi bawaan dan _generate_ kunci aplikasi:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database & Mapbox**
   Buka file `.env`, atur koneksi `DB_*` kamu, dan tambahkan Token Mapbox:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=urban_map
   DB_USERNAME=root
   DB_PASSWORD=

   MAPBOX_TOKEN=pk.eyJ1... (Isi dengan token Mapbox kamu)
   ```

5. **Jalankan Migrasi & Seeder Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Kompilasi Aset Frontend & Jalankan Server**
   ```bash
   # Terminal 1 (Vite)
   npm run dev
   
   # Terminal 2 (Laravel)
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://localhost:8000`.

## 📸 Tampilan UI

Fitur UI didesain menggunakan paradigma **Glassmorphism** dengan estetika warna yang mendalam. Terdapat panel-panel melayang (mengambang), tombol reaktif, animasi Alpine.js halus, dan penanda lokasi dengan efek pancaran _glow_ / _pulse_ yang meningkatkan nuansa seram / _spooky_.

## 🤝 Kontribusi

Kami sangat menyambut kontribusi! Jika Anda menemukan _bug_ atau memiliki ide fitur tambahan:
1. _Fork_ repositori ini.
2. Buat _branch_ baru (`git checkout -b fitur/ide-keren-anda`).
3. Lakukan _Commit_ (`git commit -m 'Menambahkan fitur keren'`).
4. Lakukan _Push_ ke _branch_ (`git push origin fitur/ide-keren-anda`).
5. Buat _Pull Request_.

## 📄 Lisensi

Proyek ini berada di bawah [MIT License](https://opensource.org/licenses/MIT). Anda bebas menggunakan, memodifikasi, dan mendistribusikannya untuk keperluan apapun.
