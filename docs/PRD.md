# Product Requirement Document (PRD) - SIMPEG PPPA

## 1. Project Overview

**SIMPEG PPPA** is an internal Personnel Management Information System (Sistem Informasi Kepegawaian) designed for the sub-department of personnel management (Kasubag Kepegawaian). The platform streamlines employee data tracking, automates periodic salary increments (Kenaikan Gaji Berkala - KGB), and manages employee leave (Cuti) requests, including document generation.

---

## 2. Tech Stack & Architecture Anchors

* **Backend Framework:** Laravel 11
* **Frontend Framework:** React (TypeScript) via Inertia.js (SPA Workflow)
* **Routing Engine:** `laravel/wayfinder` (Attributes/File-based routing)
* **Access Control:** `spatie/laravel-permission` (Role-Based Access Control)
* **Database:** SQLite

---

## 3. User Roles & Permissions (RBAC)

The system operates strictly under two defined roles managed via Spatie Permission:

| Role | Target Model | Scope & Permissions |
| --- | --- | --- |
| **Admin** *(Kasubag Kepegawaian)* | `User` / `Pegawai` | Full CRUD on Employees, Approval & Printing for Cuti, Processing, sending, and printing KGB files. Access to master configurations. |
| **Pegawai** *(Employee)* | `User` / `Pegawai` | Read-only profile access, request Cuti, view historical Cuti & KGB status. Restricted from modifying other records. |

---

## 4. Functional Requirements & Feature Matrix

### 4.1 Dashboard Module

* **Admin View:** Displays statistical widgets containing total active employees, pending leave requests, and upcoming KGB reviews within the current month.
* **Pegawai View:** Displays personal quick-status widgets (remaining annual leave count, current rank/pangkat, and status of the latest submission).

### 4.2 Employee Management Module (Manajemen Pegawai)

* **Scope:** Admin exclusive.
* **Capabilities:**
* Full CRUD operations linking `User` credentials with `Pegawai` structural profiles.
* Manage chronological track records linked via relations: `RiwayatPangkat`, `RiwayatJabatan`, and `RiwayatPendidikan`.
* Assign sector classification (`Bidang`) and professional designations (`Jabatan`).



### 4.3 Leave Management Module (Manajemen Cuti)

* **Workflow:**
1. `Pegawai` submits a leave request form specifying date ranges and leave types. Records mutate into `Cuti` model with a default `pending` status.
2. `Admin` reviews pending requests via a centralized queue panel to mark them as `approved` or `rejected`.


* **Document Generation:**
* **Feature:** Print Leave File (*Cetak File Cuti*).
* **Requirement:** Approved entries unlock a print trigger generating an official layout containing the leave details, formatted cleanly for standard paper sizes.



### 4.4 Periodic Salary Increment Module (Manajemen KGB)

* **Workflow:**
* **KGB Form (*Form KGB*):** Admin inputs and manages evaluation metrics for salary increments stored inside the `Kgb` model.
* **Send KGB File (*Kirim File KGB*):** Admin triggers a dispatch mechanism that changes state or logs a transmission record, notifying the specific `Pegawai` that their digital KGB statement has been authorized.
* **Document Generation (*Cetak File KGB*):** Both Admin and the designated Pegawai can render and download the physical official document.



---

## 5. UI/UX & Localization Rules for AI Generation

* **Language Constraint:** All user-facing application copy, dashboard titles, data tables, structural labels, form placeholders, and toast notifications **must be in Indonesian (Bahasa Indonesia)**.
* **UI Components:** Build interface elements solely by consuming the pre-configured component library components in `resources/js/components/ui/*` (Shadcn primitives like Tables, Buttons, Dialogs, Sidebars, and Sonner alerts).
* **Form Conventions:** Implement reactive state processing via Inertia useForm hooks. Always render explicit error states utilizing `resources/js/components/input-error.tsx`.

---

## 6. Model Mapping Context for Wayfinder & Eloquent

When generating endpoints, map controllers strictly against these entities:

* `Pegawai` tracking -> `App\Models\Pegawai`
* Leave submissions -> `App\Models\Cuti`
* Salary increment cycles -> `App\Models\Kgb`

All route decorators must register via `laravel/wayfinder` configuration metrics. Avoid writing traditional global declarations inside `routes/web.php` unless configuring baseline application entrypoints.