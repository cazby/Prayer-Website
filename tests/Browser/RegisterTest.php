<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     */
    public function test_user_must_confirm_email_to_login()
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

            $browser->visit('/login')
                ->type('email', 'rdelorier@gmail.com')
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSourceHas(__('confirmation::confirmation.not_confirmed', [
                    'resend_link' => route('auth.resend_confirmation')
                ]));

            $browser->visit("register/confirm/" . User::value('confirmation_code'))
                ->assertPathIs('/login')
                ->assertSee(__('confirmation::confirmation.confirmation_successful'));

            $browser->visit('/login')
                ->type('email', 'rdelorier@gmail.com')
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/home');
        });
    }
}
