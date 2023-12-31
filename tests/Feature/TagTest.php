<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tag_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $data = [
          'title' => 'some title'
        ];

        $response = $this->actingAs($user)->post('/tags', $data);
        $response->assertOk();

        $this->assertDatabaseCount('tags', 1);
    }

    /** @test */
    public function attr_title_is_required_for_storing_tag()
    {
        $user = User::factory()->create();

        $data = [
            'title' => ''
        ];

        $response = $this->actingAs($user)->post('/tags', $data);
        $response->assertRedirect();
        $response->assertInvalid('title');
    }

    /** @test */
    public function a_tag_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $tag = Tag::factory()->create();

        $data = [
            'title' => 'updated title'
        ];

        $response = $this->actingAs($user)->patch('/tags/' . $tag->id, $data);
        $response->assertOk();

        $updTag = Tag::first();
        $this->assertEquals($data['title'], $updTag->title);

        $this->assertEquals($tag->id, $updTag->id);
    }

    /** @test */
    public function response_for_route_tag_index_is_view_tag_index()
    {
        $this->withoutExceptionHandling();

        $tags = Tag::factory(10)->create();

        $response = $this->get('/tags');
        $response->assertViewIs('tag.index');
        $response->assertSeeText('This is index page');

        $titles = $tags->pluck('title')->toArray();
        $response->assertSeeText($titles);
    }

    /** @test */
    public function response_for_route_tag_show_is_view_with_single_tag_show()
    {
        $this->withoutExceptionHandling();

        $tag = Tag::factory()->create();
        $response = $this->get('/tags/' . $tag->id);
        $response->assertViewIs('tag.show');
        $response->assertSeeText('This is show page');

    }

    /** @test */
    public function response_for_route_tag_create_is_view_tag_create()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tags/create');
        $response->assertViewIs('tag.create');
        $response->assertSeeText('This is create page');
    }

    /** @test */
    public function response_for_route_tag_edit_is_view_tag_edit()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->get('/tags/' . $tag->id . '/edit');
        $response->assertViewIs('tag.edit');
        $response->assertSeeText('This is tag edit page');
    }

    /** @test */
    public function a_tag_can_be_deleted_by_auth_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->delete('/tags/' . $tag->id);
        $response->assertRedirect();

        $this->assertSoftDeleted('tags');
    }

    /** @test */
    public function a_tag_can_be_deleted_by_only_auth_user()
    {
        $tag = Tag::factory()->create();

        $response = $this->delete('/tags/' . $tag->id);
        $response->assertRedirect();

        $this->assertDatabaseCount('tags', 1);
    }

    /** @test */
    public function a_view_tag_create_can_be_seen_by_only_auth_user()
    {
        $response = $this->get('/tags/create');
        $response->assertRedirect();
    }

    /** @test */
    public function a_view_tag_edit_can_be_seen_by_only_auth_user()
    {
        $tag = Tag::factory()->create();

        $response = $this->get('/tags/' . $tag->id . '/edit');
        $response->assertRedirect();
    }
    /** @test */
    public function a_tag_can_be_stored_by_only_auth_user()
    {
        $data = [
            'title' => 'some title'
        ];

        $response = $this->post('/tags', $data);
        $response->assertRedirect();

        $this->assertDatabaseCount('tags', 0);

    }

    /** @test */
    public function a_tag_can_be_updated_by_only_auth_user()
    {
        $tag = Tag::factory()->create();

        $data = [
            'title' => 'updated title'
        ];

        $response = $this->patch('/tags/' . $tag->id, $data);
        $response->assertRedirect();

    }
}
