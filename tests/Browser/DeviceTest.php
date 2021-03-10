<?php

namespace Tests\Browser;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeviceTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testIndex() {
        $user = User::factory()->create();

        $device = Device::factory()->create();

        $this->browse(function ($browser) use ($user) {
            $browser
                ->loginAs(User::find(1))
                ->visit();
        });

    }
}
