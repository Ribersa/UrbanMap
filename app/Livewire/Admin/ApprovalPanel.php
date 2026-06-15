<?php

namespace App\Livewire\Admin;

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
        
        session()->flash('success', "Lokasi '{$mystery->title}' berhasil disetujui dan sekarang tampil di peta publik!");
    }

    public function reject($id)
    {
        $mystery = Mystery::findOrFail($id);
        $title = $mystery->title;
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
