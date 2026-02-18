<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use App\Models\Cuti;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
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
