<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::firstOrCreate(['name' => 'blue']);
        Color::firstOrCreate(['name' => 'green']);
        Color::firstOrCreate(['name' => 'cyan']);
        Color::firstOrCreate(['name' => 'red']);
        Color::firstOrCreate(['name' => 'magenta']);
        Color::firstOrCreate(['name' => 'yellow']);
        Color::firstOrCreate(['name' => 'white']);
    }
}
