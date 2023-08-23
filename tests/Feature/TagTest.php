<?php

namespace Tests\Feature;

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
}
