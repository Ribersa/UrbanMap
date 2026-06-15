<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mystery;
use App\Models\LiveReport;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UrbanMap extends Component
{
    public $locations = [];
    public $selectedMystery = null;
    public $reportText = '';

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
        $this->dispatch('open-drawer');
    }

    public function submitReport()
    {
        if (!$this->selectedMystery) {
            return;
        }

        $this->validate([
            'reportText' => 'required|string|min:3|max:150',
        ], [
            'reportText.required' => 'Laporan tidak boleh kosong.',
            'reportText.min' => 'Laporan minimal 3 karakter.',
            'reportText.max' => 'Laporan maksimal 150 karakter.',
        ]);

        // XSS Protection: strip all HTML tags
        $sanitized = strip_tags(trim($this->reportText));

        LiveReport::create([
            'mystery_id' => $this->selectedMystery->id,
            'status_note' => $sanitized,
        ]);

        $this->reportText = '';
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

    public function render()
    {
        return view('livewire.urban-map');
    }
}
