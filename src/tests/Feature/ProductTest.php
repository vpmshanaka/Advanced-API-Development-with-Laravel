<?php 

use App\Models\User;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductTest extends TestCase
{
    // use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_create_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'detail',
                        'unit_price',
                    ],
                ],
            ]);
    }

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $productData = [
            'name' => $this->faker->word,
            'detail' => $this->faker->sentence,
            'unit_price' => $this->faker->randomFloat(2, 10, 1000),
        ];

        $response = $this->json('POST', route('products.store'), $productData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'detail',
                    'unit_price',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('products', $productData);
    }

    public function test_authenticated_user_can_list_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->json('GET', route('products.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'detail',
                        'unit_price',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_authenticated_user_can_retrieve_single_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->json('GET', route('products.show', $product->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'detail',
                    'unit_price',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_authenticated_user_can_update_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product Name',
            'detail' => 'Updated Product Detail',
            'unit_price' => 19.99,
        ];

        $response = $this->json('PUT', route('products.update', $product->id), $updatedData);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'detail',
                'unit_price',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_authenticated_user_can_delete_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->json('DELETE', route('products.destroy', $product->id));

        $response->assertStatus(200);
    }
}
