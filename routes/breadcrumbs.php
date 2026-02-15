<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
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
    $trail->push('cuti', route('admin.cuti.index'));
});
