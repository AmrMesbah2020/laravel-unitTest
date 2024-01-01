<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends BaseTestClass
{
    public function test_products_listing(): void
    {
        Product::factory(10)->create();
        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'products.data');
        $response->assertJsonIsArray('products.data');
        $response->assertJsonStructure([
            'products' => [
                'current_page',
                'data' => [
                    '*' => ['id','name', 'price']
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'links' => [
                    '*' => ['url', 'label', 'active']
                ],
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
            'message'
        ]);
    }

    public function test_create_product(): void
    {
        $product = [
            "name" => "test product",
            "price" => 99.56,
            'qty' => 50
        ];
        $response = $this->postJson('/api/products',$product);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'product' => ['id', 'name', 'price', 'qty'],
            'message'
        ]);
        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($lastProduct->name, $product['name']);
        $this->assertEquals($lastProduct->price, $product['price']);
        $this->assertEquals($lastProduct->qty, $product['qty']);
    }

    public function test_create_products_with_validation_errors()
    {
        $response = $this->postJson('/api/products/',[
            'name'=>'',
            'price' => 0,
            'qty' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();
        $updatedProduct = [
            'name'=>'product updated',
            'price' => $product->price,
            'qty' => 100
        ];

        $response = $this->putJson('/api/products/'.$product->id,$updatedProduct);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', $updatedProduct);
        $response->assertJsonStructure([
            'product' => ['id', 'name', 'price', 'qty'],
            'message'
        ]);
    }

    public function test_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson('/api/products/'. $product->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message'
        ]);
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseMissing('products', $product->toArray());
    }
}
