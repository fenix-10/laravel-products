<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
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

        $this->assertDatabaseCount('categories', 1);

        $category = Category::first();

        $this->assertEquals($data['title'], $category->title);
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

    /** @test */
    public function a_category_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $category = Category::factory()->create();

        $data = [
          'title' => 'updated title'
        ];

        $response = $this->patch('/categories/' . $category->id, $data);
        $response->assertOk();

        $updCategory = Category::first();
        $this->assertEquals($data['title'], $updCategory->title);

        $this->assertEquals($category->id, $updCategory->id);
    }
}
