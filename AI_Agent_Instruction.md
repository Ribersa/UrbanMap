# 🤖 AI Developer Agent Instructions: Urban Legend Map (Laravel 12 + Livewire 4 + Mapbox)

You are an expert full-stack Laravel developer. Your task is to build the "Urban Legend & Local Mystery Marker Map" application. Follow the strict architectural guidelines, database schema, and step-by-step prompts below.

---

## 1. System Rules & Tech Stack Vitals
* **Framework:** Laravel 12 (latest structural standards).
* **Reactivity:** Livewire 4 & Alpine.js.
* **Map Engine:** Mapbox GL JS (v3+ via CDN, do NOT use npm for Mapbox).
* **Database:** PostgreSQL (with PostGIS extensions) or MySQL (with Spatial data type).
* **Tailwind CSS:** Use full-page layout, dark theme baseline (`bg-slate-950`, `text-slate-100`).
* **Livewire 4 Requirement:** Always use `@script` and `@endscript` directives for asset injection. Avoid direct inline JS inside template loops. Use `wire:ignore` on map containers.

---

## 2. Directory & Component Mapping

Generate files matching this exact structure:
```text
├── app/
│   ├── Livewire/
│   │   ├── UrbanMap.php          (Main Full-Page Component)
│   │   └── Components/
│   │       ├── DrawerDetails.php (Sidebar panel info)
│   │       └── FilterPanel.php   (Category controller)
│   └── Models/
│       ├── Mystery.php
│       └── LiveReport.php

---

## 3. Database Schema Blueprint

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
    $table->boolean('is_verified')->default(false);
    $table->timestamps();
    
    // Indexing for performance
    $table->index(['latitude', 'longitude']);
});

---

Migration: create_live_reports_table

Schema::create('live_reports', function (function ($table) {
    $table->id();
    $table->foreignId('mystery_id')->constrained()->onDelete('cascade');
    $table->string('status_note');
    $table->timestamps();
});