<?php

namespace Tests\Feature\Group;

use App\Group;
use App\GroupInvite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\GroupInviteCreated;
use App\Events\GroupInviteAccepted;

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

        $this->actingAs($this->group->user, 'api')
            ->json('get', route('groups.invites.index', $this->group))
            ->assertStatus(200)
            ->assertJsonFragment(['email' => $invite->email]);
    }

    /**
     * @test
     */
    public function it_should_create_group_invites()
    {
        Event::fake();

        $data = ['email' => $this->faker->email];

        $this->actingAs($this->group->user, 'api')
            ->json('post', route('groups.invites.store', $this->group), $data)
            ->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertEquals(1, $this->group->invites()->count());

        Event::assertDispatched(GroupInviteCreated::class, function ($e) use ($data) {
            return $e->invite->email === $data['email'];
        });
    }

    /**
     * @test
     */
    public function it_should_show_group_invites()
    {
        $invite = factory(GroupInvite::class)->create(['group_id' => $this->group->id]);

        $this->actingAs($invite->sender, 'api')
            ->json('get', route('groups.invites.show', [$this->group, $invite]))
            ->assertStatus(200)
            ->assertJsonFragment(['email' => $invite->email]);
    }

    /**
     * @test
     */
    public function it_should_allow_group_owner_to_view_invites_created_by_others()
    {
        $invite = factory(GroupInvite::class)->create(['group_id' => $this->group->id]);

        $this->actingAs($this->group->user, 'api')
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

        $this->actingAs($invite->sender, 'api')
            ->json('delete', route('groups.invites.destroy', [$this->group, $invite]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('group_invites', ['id' => $invite->id]);
    }

    /**
     * @test
     */
    public function it_should_allow_group_owner_to_delete_invites_created_by_others()
    {
        $invite = factory(GroupInvite::class)->create(['group_id' => $this->group->id]);

        $this->actingAs($this->group->user, 'api')
            ->json('delete', route('groups.invites.destroy', [$this->group, $invite]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('group_invites', ['id' => $invite->id]);
    }

    /**
     * @test
     */
    public function it_should_accept_group_invites()
    {
        Event::fake();

        $invite = factory(GroupInvite::class)
            ->states('matched')
            ->create(['group_id' => $this->group->id]);

        $this->actingAs($invite->receiver, 'api')
            ->json('get', route('group_invites.accept', $invite))
            ->assertStatus(200);

        Event::assertDispatched(GroupInviteAccepted::class);

        $this->assertDatabaseHas('group_user', [
            'group_id' => $invite->group_id,
            'user_id' => $invite->receiver_id
        ]);
    }
}
