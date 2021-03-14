<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::factory()
            ->count(3)
            ->create()
            ->each(function ($store) {
                $user = User::factory()->create();
                $store->users()->attach($user);
            });
    }
}
