<?php

namespace Tests\Feature\Comment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_no_subtitle_and_thumbnail()
    {
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => '',
            'thumbnail' => '',
            'body'      => 'This is the Description',
        ];
        $user = User::factory()->create();
        $userId = $user->id;

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);


        unset($data['thumbnail']);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'data' => array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'subtitle'     => null,
                    'thumbnail_id' => null,
                    'author'       => [
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                        'thumbnail'  => $user->thumbnail,
                    ],
                    'thumbnail'    => null,
                ],
            ),
        ]);
        $this->assertDatabaseHas(
            'posts',
            array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'subtitle'     => null,
                    'thumbnail_id' => null,
                ]
            )
        );
    }

    public function test_success_with_subtitle_and_no_thumbnail()
    {
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => '',
            'body'      => 'This is the Description',
        ];
        $user = User::factory()->create();
        $userId = $user->id;

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        unset($data['thumbnail']);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'data' => array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'thumbnail_id' => null,
                    'author'       => [
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                        'thumbnail'  => $user->thumbnail,
                    ],
                    'thumbnail'    => null,
                ],
            ),
        ]);
        $this->assertDatabaseHas(
            'posts',
            array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'thumbnail_id' => null,
                ]
            )
        );
    }

    public function test_success_all_fields_filled()
    {
        $thumbnail = UploadedFile::fake()->image('user.jpeg', 200, 200);
        $data = [
            'title'     => 'Sample Title',
            'subtitle'  => 'Subtitle',
            'thumbnail' => $thumbnail,
            'body'      => 'This is the Description',
        ];
        $user = User::factory()->create();
        $userId = $user->id;

        Storage::fake('local');
        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);

        unset($data['thumbnail']);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'data' => array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'thumbnail_id' => 1,
                    'author'       => [
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                        'thumbnail'  => $user->thumbnail,
                    ],
                    'thumbnail'    => $response->decodeResponseJson()['data']['thumbnail'],
                ],
            ),
        ]);
        $this->assertDatabaseHas(
            'posts',
            array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'thumbnail_id' => 1,
                ]
            )
        );
    }

    public function test_success_title_and_body_only()
    {
        $data = [
            'title' => 'Sample Title',
            'body'  => 'This is the Description',
        ];
        $user = User::factory()->create();
        $userId = $user->id;

        Sanctum::actingAs($user);
        $response = $this->json('POST', '/api/posts', $data);


        unset($data['thumbnail']);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'data' => array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'subtitle'     => null,
                    'thumbnail_id' => null,
                    'author'       => [
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                        'thumbnail'  => $user->thumbnail,
                    ],
                    'thumbnail'    => null,
                ],
            ),
        ]);
        $this->assertDatabaseHas(
            'posts',
            array_merge(
                $data,
                [
                    'id'           => 1,
                    'user_id'      => $userId,
                    'subtitle'     => null,
                    'thumbnail_id' => null,
                ]
            )
        );
    }

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
        $thumbnail = UploadedFile::fake()->image('user.pdf', 200, 200);
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
}
