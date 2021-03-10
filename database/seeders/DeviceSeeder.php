<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Issue;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Device::factory()
            ->hasAttached(
                Issue::factory()->count(3),
                ['deduction' => 30]
            )
            ->count(10)
            ->create();
    }
}
