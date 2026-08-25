<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        Period::create([
            'name'       => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date'   => '2026-06-30',
            'status'     => 'active',
        ]);

        Period::create([
            'name'       => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date'   => '2025-06-30',
            'status'     => 'inactive',
        ]);
    }
}