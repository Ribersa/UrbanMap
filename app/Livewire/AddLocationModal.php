<?php

namespace App\Livewire;

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

        Mystery::create([
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

        $this->successMessage = 'Lokasi berhasil diajukan! Menunggu persetujuan Admin.';
        
        // Hide modal after 3 seconds
        $this->dispatch('locationAdded');
    }

    public function render()
    {
        return view('livewire.add-location-modal');
    }
}
