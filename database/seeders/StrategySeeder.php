<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Strategy;

class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strategies = config('const.strategies');

        foreach ($strategies as $strategyName) {
            Strategy::factory()->create(['name' => $strategyName]);
        }
    }
}
