<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UrbanMap;
use App\Livewire\Admin\ApprovalPanel;

Route::get('/', UrbanMap::class);
Route::get('/admin/approval', ApprovalPanel::class)->name('admin.approval');
