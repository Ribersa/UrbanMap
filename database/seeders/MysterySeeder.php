<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\Mystery;
use App\Models\User;

class MysterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure at least one user exists
        $user = User::firstOrCreate(
            ['email' => 'admin@urbanmap.test'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        $mysteries = [
            // Jakarta
            [
                'title' => 'Menara Saidah',
                'description' => 'Gedung perkantoran terbengkalai dengan banyak laporan penampakan makhluk halus di lantai basemen dan lift yang bergerak sendiri.',
                'category' => 'penampakan',
                'scary_level' => 5,
                'latitude' => -6.2415,
                'longitude' => 106.8576,
                'is_verified' => true,
            ],
            [
                'title' => 'Terowongan Casablanca',
                'description' => 'Banyak pengendara mengaku melihat sosok nenek menyeberang jalan atau kuntilanak jika tidak membunyikan klakson.',
                'category' => 'penampakan',
                'scary_level' => 4,
                'latitude' => -6.2238,
                'longitude' => 106.8391,
                'is_verified' => true,
            ],
            [
                'title' => 'Stasiun Manggarai',
                'description' => 'Kereta hantu yang beroperasi di malam hari tanpa masinis sering terlihat oleh warga sekitar stasiun.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 4,
                'latitude' => -6.2096,
                'longitude' => 106.8502,
                'is_verified' => true,
            ],
            [
                'title' => 'Jembatan Ancol',
                'description' => 'Si Manis Jembatan Ancol, sosok wanita cantik berbaju merah yang konon tewas dibunuh dan dibuang di area ini.',
                'category' => 'penampakan',
                'scary_level' => 5,
                'latitude' => -6.1265,
                'longitude' => 106.8335,
                'is_verified' => true,
            ],
            [
                'title' => 'Rumah Pondok Indah',
                'description' => 'Rumah elit yang ditinggalkan setelah seluruh keluarga dibunuh. Konon penjual nasi goreng pernah hilang saat mengantar pesanan ke dalam.',
                'category' => 'kutukan',
                'scary_level' => 5,
                'latitude' => -6.2775,
                'longitude' => 106.7836,
                'is_verified' => true,
            ],
            [
                'title' => 'TPU Jeruk Purut',
                'description' => 'Hantu pastor tanpa kepala yang berkeliaran di malam hari mencari makamnya sambil membawa kepalanya sendiri dan diikuti anjing.',
                'category' => 'penampakan',
                'scary_level' => 5,
                'latitude' => -6.2743,
                'longitude' => 106.8143,
                'is_verified' => true,
            ],
            [
                'title' => 'Toko Merah Kota Tua',
                'description' => 'Gedung peninggalan Belanda yang menjadi saksi bisu pembantaian. Sering terdengar suara tangisan dan langkah kaki tentara.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 4,
                'latitude' => -6.1361,
                'longitude' => 106.8118,
                'is_verified' => true,
            ],
            [
                'title' => 'Museum Sejarah Jakarta',
                'description' => 'Penjara bawah tanah yang kelam, sering tercium bau anyir darah dan suara rintihan kesakitan.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 3,
                'latitude' => -6.1352,
                'longitude' => 106.8133,
                'is_verified' => false,
            ],
            // Malang
            [
                'title' => 'Hotel Niagara Lawang',
                'description' => 'Hotel klasik tua di Lawang. Banyak laporan tentang penampakan wanita Eropa di balkon dan kamar yang tidak pernah disewakan.',
                'category' => 'penampakan',
                'scary_level' => 4,
                'latitude' => -7.8347,
                'longitude' => 112.6983,
                'is_verified' => true,
            ],
            [
                'title' => 'Wisma Erni',
                'description' => 'Rumah megah di Lawang yang terbengkalai. Konon keluarga Erni dibantai dan arwahnya masih menempati rumah tersebut.',
                'category' => 'kutukan',
                'scary_level' => 5,
                'latitude' => -7.8423,
                'longitude' => 112.7001,
                'is_verified' => true,
            ],
            [
                'title' => 'SMA Tugu Malang',
                'description' => 'Lantai bercak darah yang tidak bisa dibersihkan dan penampakan tentara tanpa kepala berbaris di aula sekolah.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 4,
                'latitude' => -7.9768,
                'longitude' => 112.6326,
                'is_verified' => true,
            ],
            [
                'title' => 'Jembatan Pelor',
                'description' => 'Sering ada pengendara yang mengaku dibonceng oleh sosok putih saat melewati jembatan ini di malam hari.',
                'category' => 'penampakan',
                'scary_level' => 3,
                'latitude' => -7.9691,
                'longitude' => 112.6289,
                'is_verified' => false,
            ],
            [
                'title' => 'Universitas Brawijaya (Gedung Widyaloka)',
                'description' => 'Kerap terdengar suara ketikan mesin tik tua dan penampakan dosen gaib yang mengajar mahasiswa sendirian.',
                'category' => 'penampakan',
                'scary_level' => 3,
                'latitude' => -7.9525,
                'longitude' => 112.6138,
                'is_verified' => true,
            ],
            [
                'title' => 'Gunung Kawi',
                'description' => 'Terkenal dengan pesugihan. Banyak mitos siluman babi hutan atau hewan gaib lainnya di sekitar area pesarean.',
                'category' => 'mitos_hewan',
                'scary_level' => 5,
                'latitude' => -8.0264,
                'longitude' => 112.4578,
                'is_verified' => true,
            ],
            [
                'title' => 'Museum Brawijaya Malang',
                'description' => 'Gerbong Maut yang merenggut banyak nyawa. Bau anyir darah dan suara rintihan sering terdengar dari dalam gerbong tersebut.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 5,
                'latitude' => -7.9715,
                'longitude' => 112.6205,
                'is_verified' => true,
            ],
        ];

        foreach ($mysteries as $mysteryData) {
            $mysteryData['user_id'] = $user->id;
            $mysteryData['slug'] = Str::slug($mysteryData['title']) . '-' . Str::random(5);
            $mystery = Mystery::create($mysteryData);

            $mystery->liveReports()->create([
                'status_note' => 'Laporan awal diinput oleh admin.',
            ]);
        }
    }
}
