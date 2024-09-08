<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bidang::create(['name' => 'Kepala Dinas']);
        Bidang::create(['name' => 'IKP']);
        Bidang::create(['name' => 'Sekretariat']);
        Bidang::create(['name' => 'TKI']);
    }
}
