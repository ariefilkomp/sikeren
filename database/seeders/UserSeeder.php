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
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $user->assignRole('admin');
        User::factory(10)->create();
    }
}
