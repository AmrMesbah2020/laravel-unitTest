<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends BaseTestClass
{
    public function test_login_form(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('12345678'),
        ]);
        $response = $this->post('login', [
            'email' => 'john@example.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('products');
    }

    public function test_unauthenticated_user_cannot_acc_products()
    {
        $response = $this->get('/products');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_admin_user_can_see_products_create_button()
    {
        $response = $this->actingAs($this->admin)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Add Product');
    }

    public function test_non_admin_user_cannot_see_products_create_button()
    {
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('Add Product');
    }

    public function test_admin_user_can_access_products_create_page()
    {
        $response = $this->actingAs($this->admin)->get('/products/create');

        $response->assertStatus(200);
        $response->assertViewIs('products.create');
    }
}
