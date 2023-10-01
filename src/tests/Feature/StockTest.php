<?php 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;

class StockTest extends TestCase
{
   // use RefreshDatabase;

    /** @test */
    public function it_can_create_stock_for_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $stockData = [
            'product_id' => $product->id,
            'quantity' => 10,
        ];

        $response = $this->json('POST', route('stocks.store'), $stockData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'quantity',
                    'product_name',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('stocks', $stockData);
    }

    /** @test */
    public function it_can_list_stock_for_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Stock::factory()->count(5)->create([
            'product_id' => $product->id,
        ]);

        $response = $this->json('GET', route('stocks.index', ['product_id' => $product->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'product_name',
                        'quantity',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_can_retrieve_single_stock_entry()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $stock = Stock::factory()->create([
            'product_id' => $product->id,
        ]);

        $response = $this->json('GET', route('stocks.show', $stock->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'product_name',
                    'quantity',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /** @test */
    public function it_can_delete_stock_entry()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Stock::factory()->create();

        $stock = Stock::factory()->create([
            'product_id' => $product->id,
        ]);

        $response = $this->json('DELETE', route('stocks.destroy', $stock->id));

        $response->assertStatus(200);
    }
}
