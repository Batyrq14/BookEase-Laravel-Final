<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_be_created_via_post_request(): void
    {
        $response = $this->post(route('authors.store'), [
            'name' => 'Ernest',
            'surname' => 'Hemingway',
            'birthdate' => '1899-07-21',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('authors.index'));

        $this->assertDatabaseHas('authors', [
            'name' => 'Ernest',
            'surname' => 'Hemingway',
        ]);
    }
}