<?php

namespace App\Http\Controllers;

use App\Events\GroupInviteAccepted;
use App\Events\GroupInviteCreated;
use App\Group;
use App\GroupInvite;
use App\Http\Resources\GroupInviteCollection;
use App\Http\Resources\GroupInviteResource;
use Illuminate\Http\Request;

class GroupInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // $this->authorize(GroupInvite::class);
        return new GroupInviteCollection($group->invites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Group               $group
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $this->authorize('invite', $group);

        $invite = $group->invites()->make($request->validate([
            'email' => 'required',
        ]));

        $invite->sender_id = $request->user()->id;
        $invite->save();

        event(new GroupInviteCreated($invite));

        return new GroupInviteResource($invite);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Group       $group
     * @param \App\GroupInvite $groupInvite
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, GroupInvite $groupInvite)
    {
        $this->authorize($groupInvite);

        return new GroupInviteResource($groupInvite);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Group       $group
     * @param \App\GroupInvite $groupInvite
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupInvite $groupInvite)
    {
        $this->authorize($groupInvite);
        $groupInvite->delete();

        return response(null, 204);
    }

    /**
     * Accept a group invite.
     *
     * @param GroupInvite $groupInvite The invite to accept
     *
     * @return \Illuminate\Http\Response
     */
    public function accept(GroupInvite $groupInvite)
    {
        $this->authorize($groupInvite);

        $groupInvite->accept();
        event(new GroupInviteAccepted($groupInvite));

        return response(null, 200);
    }
}
