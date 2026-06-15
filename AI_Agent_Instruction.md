# 🤖 AI Developer Agent Instructions: Urban Legend Map (Laravel 12 + Livewire 4 + Mapbox + Fortify Authentication)

Dokumen ini adalah cetak biru mutakhir yang menggabungkan fitur peta, sistem autentikasi Laravel Fortify, kontrol multi-user (Admin & User), serta sistem moderasi (Approval) oleh Admin sebelum lokasi baru muncul di peta.

---

## 1. System Rules & Tech Stack Vitals
* **Framework:** Laravel 12 (latest structural standards).
* **Reactivity:** Livewire 4 & Alpine.js.
* **Authentication Backend:** Laravel Fortify (Headless auth backend).
* **Map Engine:** Mapbox GL JS (v3+ via CDN).
* **Database:** MySQL (InfinityFree compatible schema).
* **Tailwind CSS:** Dark theme baseline (`bg-slate-950`, `text-slate-100`).
* **Livewire 4 Requirement:** Always use `@script` and `@endscript` directives for asset injection. Use `wire:ignore` on map containers.
* **Approval Logic:** Only locations with `is_verified = true` will be drawn on the Mapbox layer. Admin panel handles toggling this state.

---

## 2. Directory & Component Mapping

Generate files matching this exact structure:
```text
├── app/
│   ├── Livewire/
│   │   ├── UrbanMap.php          (Main Map & User Report Engine)
│   │   ├── Admin/
│   │   │   └── ApprovalPanel.php (Admin dashboard to verify pins)
│   │   └── Components/
│   │       └── AddLocationModal.php (User form to submit new legends)
│   └── Models/
│       ├── User.php
│       ├── Mystery.php
│       └── LiveReport.php
---

## 3. Database Schema Blueprint

Migration: modify_users_table

Schema::table('users', function (Blueprint $table) {
    // Add role identifier: 'admin' or 'user'
    $table->string('role')->default('user'); 
});

---

Migration: create_mysteries_table

Schema::create('mysteries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('description');
    $table->enum('category', ['penampakan', 'tempat_bersejarah', 'mitos_hewan', 'kutukan']);
    $table->integer('scary_level')->default(1);
    $table->decimal('latitude', 10, 7);
    $table->decimal('longitude', 10, 7);
    $table->boolean('is_verified')->default(false); // CRITICAL: Default false, awaits Admin approval
    $table->timestamps();
    
    $table->index(['latitude', 'longitude']);
});
