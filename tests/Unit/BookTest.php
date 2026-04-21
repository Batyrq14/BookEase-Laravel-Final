<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_short_title_is_stored_and_retrieved_correctly(): void
    {
        $author = Author::create([
            'name' => 'J. K.',
            'surname' => 'Rowling',
            'birthdate' => '1965-07-31',
        ]);

        $book = Book::create([
            'title' => 'Harry Potter and the Philosopher\'s Stone',
            'short_title' => 'HP1',
            'year' => 1997,
            'author_id' => $author->id,
        ]);

        $this->assertSame('HP1', $book->short_title);
        $this->assertSame('HP1', $book->fresh()->short_title);
    }

    public function test_book_belongs_to_author(): void
    {
        $author = Author::create([
            'name' => 'Jane',
            'surname' => 'Austen',
            'birthdate' => '1775-12-16',
        ]);

        $book = Book::create([
            'title' => 'Pride and Prejudice',
            'short_title' => 'P&P',
            'year' => 1813,
            'author_id' => $author->id,
        ]);

        $this->assertInstanceOf(Author::class, $book->author);
        $this->assertSame('Jane Austen', $book->author->fullName());
    }
}