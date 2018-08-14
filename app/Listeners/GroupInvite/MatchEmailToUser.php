<?php

namespace App\Listeners\GroupInvite;

use App\User;
use App\Events\GroupInviteCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatchEmailToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GroupInviteCreated  $event
     * @return void
     */
    public function handle(GroupInviteCreated $event)
    {
        $user = User::where('email', $event->invite->email)->first();

        if ($user) {
            $user->groupInvitesReceived()->save($event->invite);
        }
    }
}
