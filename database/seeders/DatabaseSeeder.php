<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Will Lunderville',
            'email' => 'will@refreshmobile.ca',
            'role' => 'OWNER'
        ]);
        $this->call([
            DeviceSeeder::class,
            StoreSeeder::class,
        ]);
    }
}
