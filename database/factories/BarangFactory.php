<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_barang' => fake()->word(),
            'harga' => fake()->numberBetween(1000, 100000),
            'harga_grosir' => fake()->numberBetween(500, 90000),
            'stok' => fake()->numberBetween(1, 100),
            'kategori' => fake()->randomElement(['Sepeda Pasifik', 'Sepeda Listrik', 'Ban', 'Sepeda Stroller', 'Sparepart']),
            'gambar' => null,
        ];
    }
}
