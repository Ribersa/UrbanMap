<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mystery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── 1. Admin Account ───
        $admin = User::firstOrCreate(
            ['email' => 'admin@misteri.com'],
            [
                'name' => 'Admin Misteri',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // ─── 2. Regular User Accounts ───
        $user1 = User::firstOrCreate(
            ['email' => 'user@misteri.com'],
            [
                'name' => 'User Penjelajah',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user2@misteri.com'],
            [
                'name' => 'User Saksi',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // ─── 3. Mock Approved Mysteries (10 entries, is_verified = true) ───
        $mysteries = [
            [
                'user_id' => $admin->id,
                'title' => 'Menara Saidah',
                'description' => 'Gedung perkantoran terbengkalai dengan banyak laporan penampakan makhluk halus di lantai basemen dan lift yang bergerak sendiri.',
                'category' => 'penampakan',
                'scary_level' => 5,
                'latitude' => -6.2415,
                'longitude' => 106.8576,
                'is_verified' => true,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Terowongan Casablanca',
                'description' => 'Banyak pengendara mengaku melihat sosok nenek menyeberang jalan atau kuntilanak jika tidak membunyikan klakson.',
                'category' => 'penampakan',
                'scary_level' => 4,
                'latitude' => -6.2238,
                'longitude' => 106.8391,
                'is_verified' => true,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Jembatan Ancol',
                'description' => 'Si Manis Jembatan Ancol, sosok wanita cantik berbaju merah yang konon tewas dibunuh dan dibuang di area ini.',
                'category' => 'penampakan',
                'scary_level' => 5,
                'latitude' => -6.1265,
                'longitude' => 106.8335,
                'is_verified' => true,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Rumah Pondok Indah',
                'description' => 'Rumah elit yang ditinggalkan setelah seluruh keluarga dibunuh. Konon penjual nasi goreng pernah hilang saat mengantar pesanan ke dalam.',
                'category' => 'kutukan',
                'scary_level' => 5,
                'latitude' => -6.2775,
                'longitude' => 106.7836,
                'is_verified' => true,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'TPU Jeruk Purut',
                'description' => 'Hantu pastor tanpa kepala yang berkeliaran di malam hari mencari makamnya sambil membawa kepalanya sendiri.',
                'category' => 'penampakan',
                'scary_level' => 5,
                'latitude' => -6.2743,
                'longitude' => 106.8143,
                'is_verified' => true,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Hotel Niagara Lawang',
                'description' => 'Hotel klasik tua di Lawang. Banyak laporan tentang penampakan wanita Eropa di balkon dan kamar yang tidak pernah disewakan.',
                'category' => 'penampakan',
                'scary_level' => 4,
                'latitude' => -7.8347,
                'longitude' => 112.6983,
                'is_verified' => true,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Gunung Kawi',
                'description' => 'Terkenal dengan pesugihan. Banyak mitos siluman babi hutan atau hewan gaib lainnya di sekitar area pesarean.',
                'category' => 'mitos_hewan',
                'scary_level' => 5,
                'latitude' => -8.0264,
                'longitude' => 112.4578,
                'is_verified' => true,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Stasiun Manggarai',
                'description' => 'Kereta hantu yang beroperasi di malam hari tanpa masinis sering terlihat oleh warga sekitar stasiun.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 4,
                'latitude' => -6.2096,
                'longitude' => 106.8502,
                'is_verified' => true,
            ],
            [
                'user_id' => $user1->id,
                'title' => 'Toko Merah Kota Tua',
                'description' => 'Gedung peninggalan Belanda yang menjadi saksi bisu pembantaian. Sering terdengar suara tangisan dan langkah kaki tentara.',
                'category' => 'tempat_bersejarah',
                'scary_level' => 4,
                'latitude' => -6.1361,
                'longitude' => 106.8118,
                'is_verified' => true,
            ],
            [
                'user_id' => $user2->id,
                'title' => 'Wisma Erni',
                'description' => 'Rumah megah di Lawang yang terbengkalai. Konon keluarga Erni dibantai dan arwahnya masih menempati rumah tersebut.',
                'category' => 'kutukan',
                'scary_level' => 5,
                'latitude' => -7.8423,
                'longitude' => 112.7001,
                'is_verified' => true,
            ],
        ];

        foreach ($mysteries as $data) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
            $mystery = Mystery::create($data);

            // Create an initial live report for each mystery
            $mystery->liveReports()->create([
                'status_note' => 'Laporan awal diinput oleh sistem.',
            ]);
        }
    }
}
