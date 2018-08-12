<?php

namespace Tests\Feature\Account;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_confirmation_email()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'ricky',
            'city' => 'dacula',
            'state' => 'GA',
            'email' => 'rdelorier@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHas('confirmation', __('confirmation::confirmation.confirmation_info'));

        $this->assertDatabaseHas('users', [
            'name' => 'ricky',
            'email' => 'rdelorier@gmail.com',
            'confirmed_at' => null
        ]);

        Notification::assertSentTo(
            User::first(),
            config('confirmation.notification')
        );
    }
}
