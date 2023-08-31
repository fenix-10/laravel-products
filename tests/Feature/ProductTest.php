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

        Category::factory()->create();

        $product = Product::factory()
            ->for(Category::factory()->create())
            ->create();

        $data = [
            'title' => 'upd title',
            'description' => 'upd desc',
            'image' => $file,
            'category_id' => 2,
        ];

        $response = $this->patch('/products/' . $product->id, $data);
        $response->assertOk();
    }
}
