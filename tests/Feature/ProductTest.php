<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /** @test */
    public function a_product_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $file = File::create('my_image.jpg');

        Category::factory()->create();

        $data = [
            'title' => 'some title',
            'description' => 'some desc',
            'image' => $file,
            'category_id' => 1,
        ];

        $response = $this->post('/products', $data);
        $response->assertOk();

        $this->assertDatabaseCount('products', 1);

        $product = Product::first();
        $this->assertEquals($data['title'], $product->title);
        $this->assertEquals($data['description'], $product->description);
        $this->assertEquals($data['image'], $product->image);
        $this->assertEquals($data['category_id'], $product->category_id);
    }

    /** @test */
    public function attr_title_is_required_for_storing_product()
    {
        $file = File::create('my_image.jpg');

        Category::factory()->create();

        $data = [
            'title' => '',
            'description' => 'some desc',
            'image' => $file,
            'category_id' => 1,
        ];

        $response = $this->post('/products', $data);

        $response->assertRedirect();
        $response->assertInvalid('title');
    }

    /** @test */
    public function attr_description_is_required_for_storing_product()
    {
        $file = File::create('my_image.jpg');

        Category::factory()->create();

        $data = [
            'title' => 'some title',
            'description' => '',
            'image' => $file,
            'category_id' => 1,
        ];

        $response = $this->post('/products', $data);

        $response->assertRedirect();
        $response->assertInvalid('description');
    }

    /** @test */
    public function attr_image_is_required_for_storing_product()
    {

        Category::factory()->create();

        $data = [
            'title' => 'some title',
            'description' => 'some desc',
            'image' => '',
            'category_id' => 1,
        ];

        $response = $this->post('/products', $data);

        $response->assertRedirect();
        $response->assertInvalid('image');
    }

    /** @test */
    public function attr_category_id_is_required_for_storing_product()
    {
        $file = File::create('my_image.jpg');

        Category::factory()->create();

        $data = [
            'title' => 'some title',
            'description' => 'some desc',
            'image' => $file,
            'category_id' => '',
        ];

        $response = $this->post('/products', $data);

        $response->assertRedirect();
        $response->assertInvalid('category_id');

    }

    /** @test */
    public function a_product_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $file = File::create('image.jpg');

        $newCategoryId = Category::factory()->create()->id;

        $product = Product::factory()->create();

        $data = [
            'title' => 'upd title',
            'description' => 'upd desc',
            'image' => $file,
            'category_id' => $newCategoryId,
        ];

        $response = $this->patch('/products/' . $product->id, $data);
        $response->assertOk();
    }

    /** @test */
    public function response_for_route_product_index_is_product_index_view()
    {
        $this->withoutExceptionHandling();

        $products = Product::factory(10)->create();

        $response = $this->get('/products');
        $response->assertViewIs('product.index');
        $response->assertSeeText('This is index page');

        $titles = $products->pluck('title')->toArray();
        $response->assertSeeText($titles);

        $descriptions = $products->pluck('description')->toArray();
        $response->assertSeeText($descriptions);
    }

    /** @test */
    public function response_for_route_product_show_is_view_post_show()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $response = $this->get('/products/' . $product->id);
        $response->assertViewIs('product.show');
        $response->assertSeeText('This is show page');

        $title = $product->pluck('title')->toArray();
        $response->assertSeeText($title);

        $description = $product->pluck('description')->toArray();
        $response->assertSeeText($description);
    }

    /** @test */
    public function response_for_route_product_edit_is_product_edit_view()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $response = $this->get('/product/' . $product->id . '/edit');
        $response->assertViewIs('product.edit');
        $response->assertSeeText('This is edit page');
    }

    /** @test */
    public function response_for_route_products_create_is_view_product_create()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/product/create');
        $response->assertViewIs('product.create');
        $response->assertSeeText('This is create page');
    }

}
