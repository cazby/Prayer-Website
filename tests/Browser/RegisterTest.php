<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'ricky')
                ->type('city', 'dacula')
                ->type('state', 'GA')
                ->type('email', 'rdelorier@gmail.com')
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press('Register')
                ->assertPathIs('/login')
                ->assertSee(__('confirmation::confirmation.confirmation_info'));
        });
    }
}
