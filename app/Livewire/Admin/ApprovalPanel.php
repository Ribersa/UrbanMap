<?php

namespace App\Livewire\Admin;

/**
 * Migration yang dikelola oleh ApprovalPanel:
 * - 0001_01_01_000000_create_users_table.php
 * - 0001_01_01_000001_create_cache_table.php
 * - 0001_01_01_000002_create_jobs_table.php
 * - 2026_06_09_000001_add_role_to_users_table.php
 * - 2026_06_08_145651_create_live_reports_table.php
 */

use Livewire\Component;
use App\Models\Mystery;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ApprovalPanel extends Component
{
    public function mount()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Hanya Admin yang dapat mengakses halaman ini.');
        }
    }

    public function approve($id)
    {
        $mystery = Mystery::findOrFail($id);
        $mystery->update(['is_verified' => true]);
        
        \App\Entities\Mailbox::create([
            'user_id' => $mystery->user_id,
            'title' => 'Lokasi Berhasil Disetujui! 🎉',
            'message' => "Selamat, lokasi '{$mystery->title}' yang kamu ajukan telah diverifikasi oleh Admin dan sekarang sudah muncul di peta utama.",
        ]);
        
        session()->flash('success', "Lokasi '{$mystery->title}' berhasil disetujui dan sekarang tampil di peta publik!");
    }

    public function reject($id)
    {
        $mystery = Mystery::findOrFail($id);
        $title = $mystery->title;
        $userId = $mystery->user_id;

        \App\Entities\Mailbox::create([
            'user_id' => $userId,
            'title' => 'Pengajuan Lokasi Ditolak',
            'message' => "Mohon maaf, pengajuan lokasi '{$title}' kamu belum bisa diterima saat ini karena belum memenuhi kriteria atau data kurang lengkap.",
        ]);

        $mystery->delete();

        session()->flash('success', "Lokasi '{$title}' berhasil ditolak dan dihapus.");
    }

    public function render()
    {
        $unverifiedMysteries = Mystery::with('user')
                                ->where('is_verified', false)
                                ->latest()
                                ->get();

        return view('livewire.admin.approval-panel', [
            'mysteries' => $unverifiedMysteries
        ]);
    }
}
