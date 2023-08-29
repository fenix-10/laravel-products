<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\User;
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

    /** @test */
    public function response_for_route_category_index_is_view_category_index_with_categories()
    {
        $this->withoutExceptionHandling();

        $category = Category::factory(10)->create();

        $response = $this->get('/categories');
        $response->assertViewIs('category.index');
        $response->assertSeeText('This is index page');
    }

    /** @test */
    public function response_for_route_categories_show_is_view_categories_show_with_single_category()
    {
        $this->withoutExceptionHandling();

        $category = Category::factory()->create();

        $response = $this->get('/categories/' . $category->id);

        $response->assertViewIs('category.show');
        $response->assertSeeText('This is show page');
        $response->assertSeeText($category->title);
    }

    /** @test */
    public function response_for_route_category_create_is_view_category_create()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/categories/create');
        $response->assertViewIs('category.create');
        $response->assertSeeText('This is create page');
    }

    /** @test */
    public function response_for_route_category_edit_is_view_category_edit()
    {
        $this->withoutExceptionHandling();

        $category = Category::factory()->create();

        $response = $this->get('/categories/' . $category->id . '/edit');
        $response->assertViewIs('category.edit');
        $response->assertSeeText('This is edit page');
    }

    /** @test */
    public function a_category_can_be_deleted_by_auth_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $category = Category::factory()->create();

        $response = $this->actingAs($user)->delete('/categories/' . $category->id);
        $response->assertRedirect();

        $this->assertSoftDeleted('categories');

    }

    /** @test */
    public function a_category_can_be_deleted_by_only_auth_user()
    {
        $category = Category::factory()->create();

        $response = $this->delete('/categories/' . $category->id);
        $response->assertRedirect();

        $this->assertDatabaseCount('categories', 1);
    }
}
