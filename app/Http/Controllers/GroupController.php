<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::public()->paginate();

        return new GroupCollection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(Group::class);

        $group = $request->user()->groups()->create(
            $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'string|max:255',
                'url'         => 'string|max:255',
                'private'     => 'boolean',
            ])
        );

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $this->authorize($group);

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize($group);

        $group->update($request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'string|max:255',
            'url'         => 'string|max:255',
            'private'     => 'boolean',
        ]));

        return new GroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize($group);

        $group->delete();

        return response(null, 204);
    }
}
