<?php

namespace App\Livewire;

/**
 * Migration yang dikelola oleh UrbanMap:
 * - 2026_06_22_110303_create_ritual_requirements_table.php
 * - 2026_06_22_113438_create_ritual_items_table.php
 * - 2026_06_22_113531_create_ritual_experiences_table.php
 * - 2026_06_22_115308_create_ritual_acknowledgements_table.php
 * - 2026_06_22_020513_create_mystery_bookmarks_table.php
 * - 2026_06_22_020516_create_mystery_comments_table.php
 * - 2026_06_22_020519_create_scary_ratings_table.php
 */

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Mystery;
use App\Models\LiveReport;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UrbanMap extends Component
{
    use WithFileUploads;

    public $locations = [];
    public $selectedMystery = null;
    public $reportText = '';
    public $reportPhoto;

    public function updateBounds($west, $south, $east, $north)
    {
        $this->locations = Mystery::select('id', 'title', 'category', 'latitude', 'longitude')
            ->where('is_verified', true)
            ->whereBetween('longitude', [$west, $east])
            ->whereBetween('latitude', [$south, $north])
            ->withCount(['liveReports as has_recent_report' => function ($query) {
                $query->where('created_at', '>=', now()->subMinutes(10));
            }])
            ->get()
            ->toArray();
    }

    public function selectMystery($id)
    {
        $this->selectedMystery = Mystery::with(['liveReports' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);
        $this->reportText = '';
        $this->reportPhoto = null;
        $this->dispatch('open-drawer');
    }

    public function submitReport()
    {
        if (!$this->selectedMystery) {
            return;
        }

        $this->validate([
            'reportText' => 'required|string|min:3|max:150',
            'reportPhoto' => 'nullable|image|max:1024',
        ], [
            'reportText.required' => 'Laporan tidak boleh kosong.',
            'reportText.min' => 'Laporan minimal 3 karakter.',
            'reportText.max' => 'Laporan maksimal 150 karakter.',
            'reportPhoto.image' => 'File harus berupa gambar (jpg, png, gif, webp).',
            'reportPhoto.max' => 'Ukuran gambar maksimal 1MB.',
        ]);

        // XSS Protection: strip all HTML tags
        $sanitized = strip_tags(trim($this->reportText));

        $imagePath = null;
        if ($this->reportPhoto) {
            $imagePath = $this->reportPhoto->store('report-photos', 'public');
        }

        LiveReport::create([
            'mystery_id' => $this->selectedMystery->id,
            'status_note' => $sanitized,
            'image_path' => $imagePath,
        ]);

        $this->reportText = '';
        $this->reportPhoto = null;
        $this->resetValidation();

        // Refresh the selected mystery with updated reports
        $this->selectedMystery = Mystery::with(['liveReports' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($this->selectedMystery->id);
    }

    public function refreshReports()
    {
        if ($this->selectedMystery) {
            $this->selectedMystery = Mystery::with(['liveReports' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->find($this->selectedMystery->id);
        }
    }

    public function checkRadar($lat, $lng)
    {
        $earthRadius = 6371; // km
        $thresholdKm = 1;

        $mysteries = Mystery::select('id', 'title', 'latitude', 'longitude')
            ->where('is_verified', true)
            ->get();
        $nearbyNames = [];

        foreach ($mysteries as $mystery) {
            $dLat = deg2rad($mystery->latitude - $lat);
            $dLng = deg2rad($mystery->longitude - $lng);
            $a = sin($dLat / 2) * sin($dLat / 2)
                + cos(deg2rad($lat)) * cos(deg2rad($mystery->latitude))
                * sin($dLng / 2) * sin($dLng / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            if ($distance < $thresholdKm) {
                $nearbyNames[] = $mystery->title;
            }
        }

        if (count($nearbyNames) > 0) {
            $names = implode(', ', array_slice($nearbyNames, 0, 3));
            $this->dispatch('radar-alert', [
                'detected' => true,
                'message' => "⚠️ Peringatan: Aktivitas mistis terdeteksi di dekatmu! Lokasi: {$names}",
                'count' => count($nearbyNames),
            ]);
        } else {
            $this->dispatch('radar-alert', [
                'detected' => false,
                'message' => '✅ Aman. Tidak ada aktivitas mistis dalam radius 1 KM.',
                'count' => 0,
            ]);
        }
    }

    public function deleteMail($id)
    {
        if (auth()->check()) {
            \App\Entities\Mailbox::where('id', $id)->where('user_id', auth()->id())->delete();
        }
    }

    public $experienceStory = '';
    public $experienceRitualId = null;

    public function acknowledgeRitual($ritualId)
    {
        if (!auth()->check()) return;

        $ritual = \App\Models\RitualRequirement::find($ritualId);
        if ($ritual) {
            $ritual->ritualAcknowledgements()->create([
                'user_id' => auth()->id()
            ]);
            $this->selectMystery($this->selectedMystery->id);
        }
    }

    public function submitExperience()
    {
        if (!auth()->check() || !$this->experienceRitualId) return;

        $this->validate([
            'experienceStory' => 'required|string|min:5'
        ]);

        $ritual = \App\Models\RitualRequirement::find($this->experienceRitualId);
        if ($ritual) {
            $ritual->ritualExperiences()->create([
                'user_id' => auth()->id(),
                'story' => $this->experienceStory,
                'witness_count' => 0
            ]);
            $this->experienceStory = '';
            $this->experienceRitualId = null;
            $this->selectMystery($this->selectedMystery->id);
        }
    }

    public function witnessExperience($expId)
    {
        if (!auth()->check()) return;

        $exp = \App\Entities\RitualExperience::find($expId);
        if ($exp) {
            $exp->increment('witness_count');
            $this->selectMystery($this->selectedMystery->id);
        }
    }

    public function markMailboxAsRead()
    {
        if (auth()->check()) {
            \App\Entities\Mailbox::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);
        }
    }

    public function render()
    {
        $mailboxes = auth()->check() ? \App\Entities\Mailbox::where('user_id', auth()->id())->latest()->get() : collect();

        return view('livewire.urban-map', [
            'mailboxes' => $mailboxes
        ]);
    }
}
