<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'barang_id' => \App\Models\Barang::factory(),
            'jumlah' => fake()->numberBetween(1, 10),
            'harga_barang' => fake()->numberBetween(1000, 100000),
            'total_harga' => fake()->numberBetween(1000, 1000000),
            'uang_dibayar' => fake()->numberBetween(1000, 1000000),
            'kembalian' => fake()->numberBetween(0, 100000),
            'tanggal_pembelian' => fake()->date(),
            'tipe_pembayaran' => fake()->randomElement(['tunai', 'transfer']),
            'alamat_pengantaran' => fake()->address(),
            'status' => fake()->randomElement(['selesai', 'batal', 'return', 'return_partial']),
        ];
    }
}
