<?php

namespace Tests\Feature;

use App\Models\Tag;
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

        $data = [
          'title' => 'some title'
        ];

        $response = $this->post('/tags', $data);
        $response->assertOk();

        $this->assertDatabaseCount('tags', 1);
    }

    /** @test */
    public function attr_title_is_required_for_storing_tag()
    {
        $data = [
            'title' => ''
        ];

        $response = $this->post('/tags', $data);
        $response->assertRedirect();
    }

    /** @test */
    public function a_tag_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $tag = Tag::factory()->create();

        $data = [
            'title' => 'updated title'
        ];

        $response = $this->patch('/tags/' . $tag->id, $data);
        $response->assertOk();

        $updTag = Tag::first();
        $this->assertEquals($data['title'], $updTag->title);

        $this->assertEquals($tag->id, $updTag->id);
    }
}
