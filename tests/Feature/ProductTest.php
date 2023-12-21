<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
    }

    public function test_page_contains_empty_table(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertSee('No products yet.');
    }

    public function test_page_contains_non_empty_table(): void
    {
        $user = User::factory()->create();
        Product::create([
            'name' => 'test prod.',
            'price' => 120,
            'qty' => 10
        ]);
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertDontSee('No products yet.');
    }


    public function test_view_contains_specific_object(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => fake()->name,
            'price' => random_int(0,50),
            'qty' => random_int(0,50)
        ]);
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use($product){
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_does_not_contain_11th_record(): void
    {
        $user = User::factory()->create();
        $products = Product::factory(15)->create();
        $lastProduct = $products->last();
        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

    public function test_create_product_successfully()
    {
        $product = [
            'name' => 'new product',
            'price' => 22,
            'qty' => 230
        ];
        $this->actingAs($this->admin)->post('/products', $product);

        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($lastProduct->name, $product['name']);
        $this->assertEquals($lastProduct->price, $product['price']);
        $this->assertEquals($lastProduct->qty, $product['qty']);
    }

    public function test_edit_product_form_has_correct_values()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get('/products/'.$product->id.'/edit');

        $response->assertViewHas('product',$product);
    }

    public function test_product_update_validation_errors_redirect_back_to_form()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->put('/products/'.$product->id,[
            'name'=>'',
            'price' => 0,
            'qty' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'price', 'qty']);
    }

    public function test_product_delete_successfully()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/products/'.$product->id);

        $response->assertStatus(302);
        $response->assertRedirect('/products');
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseMissing('products', $product->toArray());
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create([
            'is_admin' => $isAdmin
        ]);
    }
}
