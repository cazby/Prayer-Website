<?php

namespace App\Listeners\GroupInvite;

use App\Events\GroupInviteCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Notifications\Dispatcher;
use App\Notifications\GroupInviteCreated as InviteNotification;

class SendCreatedNotification
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
        ($event->invite->receiver ?? $event->invite)->notify(
            new InviteNotification($event->invite)
        );
    }
}
