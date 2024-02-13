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
     * Product create request 
     */
    public function test_product_create_request(): void
    {
        $arrangedProductJson = [
            'name' => 'TestProduct',
            'description' => 'TestDescription',
            'price' => 1000,
            'customfields' => json_encode([
                "passenger" => 7,
                "licenseNumber" => "1234567890",
                "syakenDate" => "2023-02-13",
                "tenkenDate" => "2024-02-13",
                "isSmokingAllowed" => 1
            ])
        ];

        $response = $this->postJson('/api/products', $arrangedProductJson);

        $response->assertStatus(201)
                ->assertJson(fn (AssertableJson $json) => 
                    $json->whereType('id', 'integer')
                    ->where('name', 'TestProduct')
                    ->where('description', 'TestDescription')
                    ->where('price', 1000)
                    ->where('status', 1)
                    ->where('customfields', '{"passenger":7,"licenseNumber":"1234567890","syakenDate":"2023-02-13","tenkenDate":"2024-02-13","isSmokingAllowed":1}')
                    ->etc()
                );
    }
    
    /**
     * Product get request test
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
