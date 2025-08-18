<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Table::updateOrCreate(
            ['table_code' => 'T001'],
            [
                'size' => 4,
                'location' => 'Entrance',
                'description' => 'Default demo table',
                'restaurant_id' => 1,
                'status' => true,
            ]
        );
    }
}
