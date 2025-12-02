<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanItem;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin Tunggal
        $admin = User::firstOrCreate(
            ['email' => 'admin@iot.id'],
            [
                'name' => 'Admin Utama',
                'identifier' => 'A' . fake()->unique()->numerify('####'),
                'role' => 'admin',
                'password' => Hash::make('password'),
                'id_card_photo_path' => 'id_cards/admin-default.jpg',
                'whatsapp_number' => '628' . fake()->numerify('##########'),
            ]
        );

        // 2. Buat Beberapa Dosen
        $dosens = User::factory()->dosen()->count(5)->create([
            'password' => Hash::make('password'),
        ]);

        // 3. Buat Banyak Mahasiswa
        $mahasiswas = User::factory()->count(20)->create([
            'password' => Hash::make('password'),
        ]);

        // 4. Buat Data Inventaris Barang
        $items = Item::factory()->count(5)->create();

        // 5. Buat beberapa barang yang rusak untuk pengujian kondisi
        $brokenItems = Item::factory()->count(3)->broken()->create();

        // 6. Buat data peminjaman (Loan) acak
        $loans = Loan::factory()->count(15)->create();

        // 7. Buat LoanItem sesuai loan yang dibuat
        foreach ($loans as $loan) {

            // Pilih item random 1 sampai 3
            $selectedItems = Item::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($selectedItems as $item) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'item_id' => $item->id,
                    'quantity' => fake()->numberBetween(1, 2),
                ]);
            }
        }
    }
}