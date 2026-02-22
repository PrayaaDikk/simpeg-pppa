<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\RiwayatJabatan;
use App\Models\RiwayatKepangkatan;
use App\Models\RiwayatPendidikan;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


// Dashboard
Breadcrumbs::for('admin', function (BreadcrumbTrail $trail) {
    $trail->push('Admin', route('admin.dashboard'));
});

// Dashboard
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push('Dashboard', route('admin.dashboard'));
});

// Pegawai
Breadcrumbs::for('admin.pegawai.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push('Pegawai', route('admin.pegawai.index'));
});

Breadcrumbs::for('admin.pegawai.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.pegawai.index');
    $trail->push('Tambah Pegawai', route('admin.pegawai.create'));
});

Breadcrumbs::for('admin.pegawai.show', function (BreadcrumbTrail $trail, $pegawai) {
    $trail->parent('admin.pegawai.index');
    $trail->push('Detail Pegawai', route('admin.pegawai.show', $pegawai->id));
    $trail->push($pegawai->nama, route('admin.pegawai.show', $pegawai->id));
});

Breadcrumbs::for('admin.pegawai.edit', function (BreadcrumbTrail $trail, $pegawai) {
    $trail->parent('admin.pegawai.index');
    $trail->push('Edit Pegawai', route('admin.pegawai.edit', $pegawai->id));
    $trail->push($pegawai->nama, route('admin.pegawai.edit', $pegawai->id));
});

Breadcrumbs::for('admin.riwayat-pendidikan.index', function (BreadcrumbTrail $trail, $pegawaiId) {
    $trail->parent('admin.pegawai.index');
    $trail->push('Riwayat Pendidikan', route('admin.riwayat-pendidikan.index', $pegawaiId));
    $trail->push(Pegawai::find($pegawaiId)->nama, route('admin.riwayat-pendidikan.index', $pegawaiId));
});

Breadcrumbs::for('admin.riwayat-pendidikan.create', function (BreadcrumbTrail $trail, $pegawaiId) {
    $trail->parent('admin.riwayat-pendidikan.index', $pegawaiId);
    $trail->push('Tambah Riwayat Pendidikan', route('admin.riwayat-pendidikan.create', $pegawaiId));
});

Breadcrumbs::for('admin.riwayat-pendidikan.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.riwayat-pendidikan.index', RiwayatPendidikan::find($id)->pegawai_id);
    $trail->push('Detail Riwayat Pendidikan', route('admin.riwayat-pendidikan.show', $id));
});

Breadcrumbs::for('admin.riwayat-pendidikan.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.riwayat-pendidikan.index', RiwayatPendidikan::find($id)->pegawai_id);
    $trail->push('Edit Riwayat', route('admin.riwayat-pendidikan.edit', $id));
});

Breadcrumbs::for('admin.riwayat-pangkat.index', function (BreadcrumbTrail $trail, $pegawaiId) {
    $trail->parent('admin.pegawai.index');
    $trail->push('Riwayat Pangkat', route('admin.riwayat-pangkat.index', $pegawaiId));
    $trail->push(Pegawai::find($pegawaiId)->nama, route('admin.riwayat-pangkat.index', $pegawaiId));
});

Breadcrumbs::for('admin.riwayat-pangkat.create', function (BreadcrumbTrail $trail, $pegawaiId) {
    $trail->parent('admin.riwayat-pangkat.index', $pegawaiId);
    $trail->push('Tambah Riwayat Pangkat', route('admin.riwayat-pangkat.create', $pegawaiId));
});

Breadcrumbs::for('admin.riwayat-pangkat.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.riwayat-pangkat.index', RiwayatKepangkatan::find($id)->pegawai_id);
    $trail->push('Detail Riwayat Pangkat', route('admin.riwayat-pangkat.show', $id));
});

Breadcrumbs::for('admin.riwayat-pangkat.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.riwayat-pangkat.index', RiwayatKepangkatan::find($id)->pegawai_id);
    $trail->push('Edit Riwayat', route('admin.riwayat-pangkat.edit', $id));
});

Breadcrumbs::for('admin.riwayat-jabatan.index', function (BreadcrumbTrail $trail, $pegawaiId) {
    $trail->parent('admin.pegawai.index');
    $trail->push('Riwayat Jabatan', route('admin.riwayat-jabatan.index', $pegawaiId));
    $trail->push(Pegawai::find($pegawaiId)->nama, route('admin.riwayat-jabatan.index', $pegawaiId));
});

Breadcrumbs::for('admin.riwayat-jabatan.create', function (BreadcrumbTrail $trail, $pegawaiId) {
    $trail->parent('admin.riwayat-jabatan.index', $pegawaiId);
    $trail->push('Tambah Riwayat Jabatan', route('admin.riwayat-jabatan.create', $pegawaiId));
});

Breadcrumbs::for('admin.riwayat-jabatan.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.riwayat-jabatan.index', RiwayatJabatan::find($id)->pegawai_id);
    $trail->push('Detail Riwayat Jabatan', route('admin.riwayat-jabatan.show', $id));
});

Breadcrumbs::for('admin.riwayat-jabatan.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.riwayat-jabatan.index', RiwayatJabatan::find($id)->pegawai_id);
    $trail->push('Edit Riwayat', route('admin.riwayat-jabatan.edit', $id));
});

// Cuti
Breadcrumbs::for('admin.cuti.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push('Cuti', route('admin.cuti.index'));
});

Breadcrumbs::for('admin.cuti.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.cuti.index');
    $trail->push('Tambah Cuti', route('admin.cuti.create'));
});

Breadcrumbs::for('admin.cuti.show', function (BreadcrumbTrail $trail, Cuti $cuti) {
    $trail->parent('admin.cuti.index');
    $trail->push('Detail Cuti', route('admin.cuti.show', $cuti->id));
});
