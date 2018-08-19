<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the group.
     *
     * @param \App\User  $user
     * @param \App\Group $group
     *
     * @return mixed
     */
    public function view(User $user, Group $group)
    {
        return $group->private
            ? false // check if user is in group
            : true;
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param \App\User  $user
     * @param \App\Group $group
     *
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $this->delete($user, $group);
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param \App\User  $user
     * @param \App\Group $group
     *
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $user->is($group->user);
    }

    public function invite(User $user, Group $group)
    {
        return $this->delete($user, $group);
    }
}
