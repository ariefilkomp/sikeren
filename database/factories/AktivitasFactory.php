<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aktivitas>
 */
class AktivitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $file = ['surat.pdf','surat.jpeg'];
        return [
            'user_id' => rand(1,10),
            'aktivitas' => $this->faker->sentence(),
            'penyelenggara' => 'Diskominfo',
            'waktu_mulai' => $this->faker->dateTimeBetween('-8 days', '+8 days'),
            'tempat' => $this->faker->sentence(2),
            'disposisi' => $this->faker->sentence(2),
            'file' => $file[rand(0,1)],
        ];
    }
}
