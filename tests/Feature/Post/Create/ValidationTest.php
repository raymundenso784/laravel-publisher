<?php

namespace Tests\Feature\Post\Create;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_fields_empty_data_sent()
    {
        $data = [];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'title' => ['The title field is required.'],
            'body'  => ['The body field is required.']
        ]);
    }

    public function test_required_fields_empty_title_and_body()
    {
        $data = [
            'title'     => '',
            'subtitle'  => '',
            'thumbnail' => '',
            'body'      => '',
        ];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'title' => ['The title field is required.'],
            'body'  => ['The body field is required.']
        ]);
    }

    public function test_invalid_thumbnail_extension()
    {
        $thumbnail = UploadedFile::fake()->create('document.pdf', 1000);
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => $thumbnail,
            'body'      => 'This is the Description',
        ];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'thumbnail' => ['The thumbnail field must be a file of type: jpg, jpeg, png.'],
        ]);
    }

    public function test_invalid_published_value_string_with_thumbnail()
    {
        $thumbnail = UploadedFile::fake()->image('user.jpg', 200, 200);
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => $thumbnail,
            'body'      => 'This is the Description',
            'published' => 'a'
        ];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'published' => ['The selected published is invalid.'],
        ]);
    }

    public function test_invalid_published_value_string_with_no_thumbnail()
    {
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => '',
            'body'      => 'This is the Description',
            'published' => 'a'
        ];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'published' => ['The selected published is invalid.'],
        ]);
    }

    public function test_invalid_published_value_wrong_integer_with_thumbnail()
    {
        $thumbnail = UploadedFile::fake()->image('user.jpg', 200, 200);
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => $thumbnail,
            'body'      => 'This is the Description',
            'published' => 2
        ];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'published' => ['The selected published is invalid.'],
        ]);
    }

    public function test_invalid_published_value_wrong_integer_with_no_thumbnail()
    {
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => '',
            'body'      => 'This is the Description',
            'published' => 2
        ];
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'published' => ['The selected published is invalid.'],
        ]);
    }
}
