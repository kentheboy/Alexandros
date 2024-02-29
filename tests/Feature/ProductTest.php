<?php

namespace Tests\Feature;

use App\Models\Alexandros\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Http\Client\Exception\RequestException;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

class ProductTest extends TestCase
{

    // use RefreshDatabase;

    /**
     * Product create request test
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
     * Product create request with image files test
     */
    public function test_product_create_request_with_images(): void 
    {

        $jpegTestFilePath = "./tests/salmplefiles/alexandros.jpg";
        $pngTestFilePath = "./tests/salmplefiles/alexandros.png";
        $arrangedProductJson = [
            'name' => 'TestProduct',
            'description' => 'TestDescription',
            'price' => 1000,
            'images' => json_encode([
                'image/jpeg;base64,' . base64_encode(file_get_contents($jpegTestFilePath)),
                'image/png;base64,' . base64_encode(file_get_contents($pngTestFilePath)),
            ]),
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
                    $json->where('images', fn (string $imageJson) => 
                        count(json_decode($imageJson)) == 2
                    )
                    ->etc()
                );
    }

    /**
     * Product create request with invalid forceful status input test
     */
    public function test_product_create_request_with_invalid_force_status() :void
    {
        $arrangedProductJson = [
            'name' => 'TestProduct',
            'description' => 'TestDescription',
            'price' => 1000,
            'status' => rand(2,10), //any number other than 0 and 1
            'customfields' => json_encode([
                "passenger" => 7,
                "licenseNumber" => "1234567890",
                "syakenDate" => "2023-02-13",
                "tenkenDate" => "2024-02-13",
                "isSmokingAllowed" => 1
            ])
        ];

        $response = $this->postJson('/api/products', $arrangedProductJson);

        $response->assertStatus(422); // The request was well-formed but was unable to be followed due to semantic errors.
    }
    
    /**
     * Product create request with forceful status input test
     */
    public function test_product_create_request_with_force_status() :void
    {
        $arrangedProductJson = [
            'name' => 'TestProduct',
            'description' => 'TestDescription',
            'price' => 1000,
            'status' => 0,
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
                    $json->where('status', 0)
                    ->etc()
                );
    }

    /**
     * Product create request with the `end_date` stated before `start_date`
     */
    public function test_product_create_request_with_invalid_publish_date_config() :void
    {
        $arrangedProductJson = [
            'name' => 'TestProduct$response->$this',
            'description' => 'TestDescription',
            'price' => 1000,
            'end_date' => '1999-02-23'
        ];

        $response = $this->postJson('/api/products', $arrangedProductJson);

        $response->assertStatus(422);
    }

    /**
     * Product read request with product id
     */
    public function test_product_read_request() :void
    {
        $arrangedProductId = $this->create_product_with_image_http_request();

        $response = $this->getJson('/api/products/' . $arrangedProductId);
        
        $statusArray = [0, 1, 2];
        $response->assertJson(fn (AssertableJson $json) => 
            $json->whereType('id', 'integer')
            ->whereType('name', 'string|null')
            ->whereType('description', 'string|null')
            ->whereType('price', 'integer')
            ->where('status', fn(int $status) => in_array($status, $statusArray))
            ->whereType('start_at', 'string')
            ->whereType('end_at', 'string|null')
            ->whereType('customfields', 'string')
            ->where('images', fn (string $imageJson) => 
                count(json_decode($imageJson)) == 2
            )
            ->where('images', fn (string $imageJson) => $this->is_images_accessibility($imageJson))
            ->whereType('updated_at', 'string')
            ->whereType('created_at', 'string')
            ->etc()
    );
    }

    /**
     * Product read request with un-existing product id
     */
    public function test_product_read_request_with_invalid_id() :void
    {
        $arrangedProductRecord = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $arrangedProductRecord->id + 1);

        $response->assertStatus(404);
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

    private function create_product_with_image_http_request() {
        $jpegTestFilePath = "./tests/salmplefiles/alexandros.jpg";
        $pngTestFilePath = "./tests/salmplefiles/alexandros.png";
        $arrangedProductJson = [
            'name' => 'TestProduct',
            'description' => 'TestDescription',
            'price' => 1000,
            'images' => json_encode([
                'image/jpeg;base64,' . base64_encode(file_get_contents($jpegTestFilePath)),
                'image/png;base64,' . base64_encode(file_get_contents($pngTestFilePath)),
            ]),
            'customfields' => json_encode([
                "passenger" => 7,
                "licenseNumber" => "1234567890",
                "syakenDate" => "2023-02-13",
                "tenkenDate" => "2024-02-13",
                "isSmokingAllowed" => 1
            ])
        ];

        $response = $this->postJson('/api/products', $arrangedProductJson);

        return $response->baseResponse->original->id;
    }

    private function is_images_accessibility(string $images) {
        $images = json_decode($images);
        foreach ($images as $url) {
            Log::info($url);
            try {
                $result = Http::get($url);
            } catch (RequestException) {
                Log::info("failed");
                return false;
            }
            
            if ($result->failed() || $result->status() === 404) {
                Log::info("failed2");
                return false;
            }
        }
        Log::info("test");
        return true;
    }
    
}
