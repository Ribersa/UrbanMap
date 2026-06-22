<?php

namespace App\Livewire;

/**
 * Migration yang dikelola oleh AddLocationModal:
 * - 2026_06_08_145607_create_mysteries_table.php
 * - 2026_06_15_043140_add_image_path_to_mysteries_and_live_reports.php
 * - 2026_06_22_020511_create_categories_table.php
 * - 2026_06_22_020514_create_media_proofs_table.php
 */

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Mystery;
use Illuminate\Support\Str;

class AddLocationModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    
    public $title;
    public $category;
    public $description;
    public $scary_level = 1;
    public $latitude;
    public $longitude;
    public $photo;
    public $rituals = [];


    public $successMessage = '';

    protected $listeners = ['openAddLocationModal' => 'openModal'];

    public function openModal($lat, $lng)
    {
        if (!auth()->check()) {
            return;
        }
        
        $this->resetForm();
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->category = '';
        $this->description = '';
        $this->scary_level = 1;
        $this->latitude = '';
        $this->longitude = '';
        $this->photo = null;
        $this->successMessage = '';
        $this->resetValidation();
    }

    public function saveLocation()
    {
        if (!auth()->check()) {
            return;
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:penampakan,tempat_bersejarah,mitos_hewan,kutukan',
            'description' => 'required|string|max:1000',
            'scary_level' => 'required|integer|min:1|max:5',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:1024',
        ], [
            'photo.image' => 'File harus berupa gambar (jpg, png, gif, webp).',
            'photo.max' => 'Ukuran gambar maksimal 1MB.',
        ]);

        $imagePath = null;
        if ($this->photo) {
            $imagePath = $this->photo->store('mysteries-photos', 'public');
        }

        $mystery = Mystery::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . Str::random(5),
            'description' => $this->description,
            'category' => $this->category,
            'scary_level' => $this->scary_level,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_verified' => false,
            'image_path' => $imagePath,
        ]);

        foreach ($this->rituals as $ritualData) {
            if (empty(trim($ritualData['instruction']))) continue;

            $req = $mystery->ritualRequirements()->create([
                'instruction' => $ritualData['instruction'],
                'ritual_type' => $ritualData['ritual_type'] ?? 'pantangan',
                'risk_level' => $ritualData['risk_level'] ?? 1,
            ]);

            if (!empty($ritualData['items'])) {
                foreach ($ritualData['items'] as $itemData) {
                    if (empty(trim($itemData['item_name']))) continue;
                    $req->ritualItems()->create([
                        'item_name' => $itemData['item_name'],
                        'quantity' => $itemData['quantity'] ?? 1,
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }
        }

        $this->successMessage = 'Lokasi berhasil diajukan! Menunggu persetujuan Admin.';
        
        // Hide modal after 3 seconds
        $this->dispatch('locationAdded');
    }

    public function addRitual()
    {
        $this->rituals[] = [
            'instruction' => '',
            'ritual_type' => 'pantangan',
            'risk_level' => 1,
            'items' => []
        ];
    }

    public function removeRitual($index)
    {
        unset($this->rituals[$index]);
        $this->rituals = array_values($this->rituals);
    }

    public function addRitualItem($index)
    {
        if (!isset($this->rituals[$index]['items'])) {
            $this->rituals[$index]['items'] = [];
        }
        
        $this->rituals[$index]['items'][] = [
            'item_name' => '',
            'quantity' => 1,
            'notes' => ''
        ];
    }

    public function removeRitualItem($ritualIndex, $itemIndex)
    {
        unset($this->rituals[$ritualIndex]['items'][$itemIndex]);
        $this->rituals[$ritualIndex]['items'] = array_values($this->rituals[$ritualIndex]['items']);
    }

    public function render()
    {
        return view('livewire.add-location-modal');
    }
}
