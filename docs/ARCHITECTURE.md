# SIMPEG PPPA - Project Architecture & Folder Guide

This document provides the definitive context for the repository layout. Use this as a reference mapping before creating, modifying, or deleting files.

## Technical Stack Overview
- **Backend:** Laravel 11 (PHP)
- **Authentication:** Laravel Fortify (Actions stored in `app/Actions/Fortify`)
- **Authorization:** Spatie Laravel-Permission (Roles & Permissions)
- **Frontend & Bridge:** React (TypeScript) with Inertia.js (`HandleInertiaRequests.php`)
- **Routing Engine:** laravel/wayfinder
- **UI Components:** Shadcn UI variants (Tailwind CSS)
- **Database:** SQLite (`database/database.sqlite`)

---

## Directory Mapping for AI Context

### 1. Backend Core (`app/`)
- `app/Models/` : **CRITICAL CONTEXT**. Contains core business entities for Personnel Management (Sistem Informasi Kepegawaian):
  - `User.php` : System credential account.
  - `Pegawai.php` : Core profile for employee/personnel data.
  - `Bidang.php`, `Jabatan.php`, `Pangkat.php` : Structural designations.
  - `Riwayat*` Models : Historical tracking logs for Career, Rank, and Education.
  - `Cuti.php`, `Kgb.php` : Leave management and periodic salary adjustment tracking (Kenaikan Gaji Berkala).
- `app/Http/Controllers/` : Holds application logic. Custom business controllers should be declared here using `laravel/wayfinder` routing parameters.
- `app/Http/Middleware/HandleInertiaRequests.php` : Shared data from backend to frontend React props (e.g., auth session, flash messages, user roles).

### 2. Database & Seeds (`database/`)
- `database/migrations/` : Contains the true definition of table definitions. Always inspect this directory before performing SQL or Eloquent queries.
- `database/seeders/` : Pre-defined master datasets for Ranks (`Pangkat`), Sectors (`Bidang`), and Positions (`Jabatan`).

### 3. Routing Layer (`routes/`)
- `routes/web.php` & `routes/settings.php` : Entrypoints for standard web requests. Driven via `laravel/wayfinder`. Do not duplicate standard `Route::` syntax if Wayfinder conventions apply.

### 4. Frontend Workspace (`resources/js/`)
- `resources/js/app.tsx` : Core Inertia.js bootsrapper client side.
- `resources/js/components/ui/` : Atomic UI library components based on Shadcn CSS primitives. **DO NOT modify files inside `ui/` unless adjusting theme configurations.**
- `resources/js/components/` : Custom layout wrappers, shell structures, and dashboard elements (e.g., `app-sidebar.tsx`, `manage-passkeys.tsx`).
- `resources/js/layouts/` : Shared structural shell frameworks for authenticated, public, and settings panels.
- `resources/js/pages/` : Page-level layout targets rendered by Inertia. 
  - Admin/Main dashboards should be structured inside `resources/js/pages/dashboard.tsx` or new feature subdirectories.

---

## AI Prompt Instructions
1. **Never guess database schemas.** Always look into `database/migrations/` or `.ai/SCHEMA.md`.
2. **Follow Inertia workflows.** Controllers must return `Inertia::render('Folder/Component', $data)`.
3. **UI Text Constraint:** Even though this architecture is in English, all Indonesian textual output for views (`resources/js/pages/`) must remain in proper Indonesian language (Bahasa Indonesia).