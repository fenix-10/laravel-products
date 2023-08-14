<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $data = [
          'title' => 'some title'
        ];

        $response = $this->post('/categories', $data);
        $response->assertOk();
    }

    /** @test */
    public function attr_title_required_for_storing_category()
    {
        $data = [
            'title' => ''
        ];

        $response = $this->post('/categories', $data);
        $response->assertRedirect();
        $response->assertInvalid('title');
    }
}
