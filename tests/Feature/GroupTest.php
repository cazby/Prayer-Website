<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_it_should_list_groups()
    {
        $count = factory(Group::class, $this->faker->numberBetween(1, 3))->create()->count();

        $response = $this->json('get', route('groups.index'))
            ->assertStatus(200)
            ->assertJsonCount($count, 'data');
    }

    public function test_it_should_not_list_private_groups()
    {
        factory(Group::class)->create();
        factory(Group::class)->state('private')->create();

        $this->json('get', route('groups.index'))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => 1])
            ->assertJsonMissing(['id' => 2]);
    }

    public function test_it_should_create_groups()
    {
        $user = factory(User::class)->create();

        $data = [
            'name'        => $this->faker->name,
            'description' => $this->faker->sentence,
            'url'         => $this->faker->url,
            'private'     => false,
        ];

        $this->actingAs($user)
            ->json('post', route('groups.store'), $data)
            ->assertStatus(201)
            ->assertJson(['data' => $data]);

        $this->assertEquals(1, $user->groups()->where($data)->count());
    }

    public function test_it_should_show_groups()
    {
        $group = factory(Group::class)->create();

        $this->actingAs($group->user)
            ->json('get', route('groups.show', $group))
            ->assertStatus(200)
            ->assertJson(['data' => $group->only('id')]);
    }

    public function test_it_should_update_groups()
    {
        $group = factory(Group::class)->create();

        $data = [
            'name'        => '--name--',
            'description' => '--desc--',
            'url'         => '--url--',
            'private'     => true,
        ];

        $this->actingAs($group->user)
            ->json('put', route('groups.update', $group), $data)
            ->assertStatus(200)
            ->assertJson(['data' => $data]);
    }

    public function test_it_should_delete_groups()
    {
        $group = factory(Group::class)->create();

        $this->actingAs($group->user)
            ->json('delete', route('groups.destroy', $group))
            ->assertStatus(204);

    }
}
