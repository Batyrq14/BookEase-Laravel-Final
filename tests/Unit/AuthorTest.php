<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_name_returns_name_and_surname(): void
    {
        $author = Author::create([
            'name' => 'George',
            'surname' => 'Orwell',
            'birthdate' => '1903-06-25',
        ]);

        $this->assertSame('George Orwell', $author->fullName());
    }

    public function test_author_has_many_books(): void
    {
        $author = Author::create([
            'name' => 'Leo',
            'surname' => 'Tolstoy',
            'birthdate' => '1828-09-09',
        ]);

        Book::create([
            'title' => 'War and Peace',
            'short_title' => 'W&P',
            'year' => 1869,
            'author_id' => $author->id,
        ]);

        Book::create([
            'title' => 'Anna Karenina',
            'short_title' => 'AK',
            'year' => 1877,
            'author_id' => $author->id,
        ]);

        $this->assertCount(2, $author->books);
        $this->assertInstanceOf(Book::class, $author->books->first());
    }
}