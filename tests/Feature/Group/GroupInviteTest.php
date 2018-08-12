<?php

namespace Tests\Feature\Group;

use App\Group;
use App\GroupInvite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupInviteTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $group;

    public function setUp()
    {
        parent::setUp();

        $this->group = factory(Group::class)->create();
    }

    /**
     * @test
     */
    public function it_should_list_group_invites()
    {
        $invite = factory(GroupInvite::class)->create([
            'group_id'  => $this->group->id,
            'sender_id' => $this->group->user->id,
        ]);

        $this->actingAs($this->group->user)
            ->json('get', route('groups.invites.index', $this->group))
            ->assertStatus(200)
            ->assertJsonFragment(['email' => $invite->email]);
    }

    /**
     * @test
     */
    public function it_should_create_group_invites()
    {
        $data = ['email' => $this->faker->email];

        $this->actingAs($this->group->user)
            ->json('post', route('groups.invites.store', $this->group), $data)
            ->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertEquals(1, $this->group->invites()->count());
    }

    /**
     * @test
     */
    public function it_should_show_group_invites()
    {
        $invite = factory(GroupInvite::class)->create(['group_id' => $this->group->id]);

        $this->actingAs($this->group->user)
            ->json('get', route('groups.invites.show', [$this->group, $invite]))
            ->assertStatus(200)
            ->assertJsonFragment(['email' => $invite->email]);
    }

    /**
     * @test
     */
    public function it_should_delete_group_invites()
    {
        $invite = factory(GroupInvite::class)->create(['group_id' => $this->group->id]);

        $this->actingAs($this->group->user)
            ->json('delete', route('groups.invites.destroy', [$this->group, $invite]))
            ->assertStatus(204);

        $this->assertEquals(0, $this->group->invites()->count());
    }
}
