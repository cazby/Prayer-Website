<?php

namespace Tests\Unit\Group;

use App\Events\GroupInviteCreated as InviteEvent;
use App\GroupInvite;
use App\Listeners\GroupInvite\MatchEmailToUser;
use App\Listeners\GroupInvite\SendCreatedNotification;
use App\Notifications\GroupInviteCreated as InviteNotification;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GroupInviteCreatedListenersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_assigns_recipient_when_it_email_matches_user()
    {
        $user = factory(User::class)->create();
        $invite = factory(GroupInvite::class)->create(['email' => $user->email]);

        (new MatchEmailToUser())->handle(new InviteEvent($invite));

        $this->assertEquals($invite->receiver_id, $user->id);
    }

    /**
     * @test
     */
    public function it_notifies_invite_if_no_user_matched()
    {
        Notification::fake();

        $invite = factory(GroupInvite::class)->create();
        (new SendCreatedNotification())->handle(new InviteEvent($invite));

        Notification::assertSentTo(
            $invite,
            InviteNotification::class
        );
    }

    /**
     * @test
     */
    public function it_notifies_user_if_assigned()
    {
        Notification::fake();

        $invite = factory(GroupInvite::class)->state('matched')->create();
        (new SendCreatedNotification())->handle(new InviteEvent($invite));

        Notification::assertSentTo(
            $invite->receiver,
            InviteNotification::class
        );

        Notification::assertNotSentTo(
            $invite,
            InviteNotification::class
        );
    }
}
