<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class DeviceTest extends DuskTestCase
{
    public function testIndex() {
        $user = User::factory()->create();

        $this->browse(function ($browser) use ($user) {
            $browser
                ->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', 'password')
                ->press('@submit')
                ->on()
                ->assertPathIs('/dashboard');
        });
    }
}
