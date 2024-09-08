<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kadin']);
        Role::create(['name' => 'kabid']);

        // Read the contents of list.txt
        $file = resource_path(). '/pegawai_kominfo.csv';
        $content = file_get_contents($file);

        // Explode the content by new line
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $parts = explode(',', $line);
            $bidang = ["KADIN" => 1, "IKP" => 2, "Sekretariat" => 3, "TKI" => 4];
            if(count($parts) > 0) {
                User::factory()->create([
                    'name' => $parts[1],
                    'no_hp' => $parts[2],
                    'bidang_id' => $bidang[trim($parts[3])],
                ]);
            }

        }
     
        $usr = User::where('no_hp', '08112592345')->first();
        $usr->assignRole('kadin');

        $usr = User::where('no_hp', '085867303777')->first();
        $usr->assignRole('kabid');

        $usr = User::where('no_hp', '081804568106')->first();
        $usr->assignRole('kabid');

        $usr = User::where('no_hp', '081327210023')->first();
        $usr->assignRole('admin');
        
    }
}
