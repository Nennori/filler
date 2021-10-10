<?php

namespace Database\Seeders;

use App\Models\Cell;
use Illuminate\Database\Seeder;

class CellSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cell::factory()->count(9801)->create();
    }
}
