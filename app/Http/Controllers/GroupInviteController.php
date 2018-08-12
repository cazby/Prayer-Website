<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupInviteCollection;
use App\Http\Resources\GroupInviteResource;
use App\GroupInvite;
use App\Group;
use Illuminate\Http\Request;

class GroupInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // authorize
        return new GroupInviteCollection($group->invites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        // authorize
        $invite = $group->invites()->make($request->validate([
            'email' => 'required'
        ]));

        $invite->sender_id = $request->user()->id;
        $invite->save();

        return new GroupInviteResource($invite);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @param  \App\GroupInvite  $groupInvite
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, GroupInvite $groupInvite)
    {
        // authorize
        return new GroupInviteResource($groupInvite);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @param  \App\GroupInvite  $groupInvite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupInvite $groupInvite)
    {
        // authorize
        $groupInvite->delete();

        return response(null, 204);
    }
}
