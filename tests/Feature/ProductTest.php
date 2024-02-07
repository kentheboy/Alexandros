<?php

namespace Tests\Feature;

use App\Models\Alexandros\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

use Illuminate\Support\Facades\Log;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_product_get_request(): void
    {
        
        Product::factory( count: 11 )->create();

        $connection = $this->app->make('Illuminate\Database\Connection');
        Log::info('Current database connection: ' . $connection->getDatabaseName());

        $response = $this->getJson('/api/products');
        // Log::info(json_encode($response));
        // dd($response);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 11)
                // ->whereAllType([
                //     'data' => 'array',
                // ])
                // ->whereCount('data', 3)
                // ->hasAll([
                //     'name',
                //     'description',
                //     'price',
                //     'images',
                //     'start_at',
                //     'end_at',
                //     'status',
                //     'customfields'
                // ])
        );
    }
    
}
