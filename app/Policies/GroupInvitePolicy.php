<?php

namespace App\Policies;

use App\GroupInvite;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupInvitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the group invite.
     *
     * @param \App\User        $user
     * @param \App\GroupInvite $groupInvite
     *
     * @return mixed
     */
    public function view(User $user, GroupInvite $groupInvite)
    {
        return $this->delete($user, $groupInvite);
    }

    /**
     * Determine whether the user can delete the group invite.
     *
     * @param \App\User        $user
     * @param \App\GroupInvite $groupInvite
     *
     * @return mixed
     */
    public function delete(User $user, GroupInvite $groupInvite)
    {
        return $user->is($groupInvite->sender) || $user->is($groupInvite->group->user);
    }

    /**
     * Determine whether the user can accept the group invite.
     *
     * @param User        $user
     * @param GroupInvite $groupInvite
     *
     * @return mixed
     */
    public function accept(User $user, GroupInvite $groupInvite)
    {
        return $user->is($groupInvite->receiver);
    }
}
