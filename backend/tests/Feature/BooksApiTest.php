<?php

namespace Tests\Feature;

use App\Models\Book;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/api/books');

        $response->assertStatus(200);
    }*/

    use RefreshDatabase;

    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();
        //dd($book->count());
        //$this->get('api/books')->dump();
        //$this->get(route('books.index'))->dump();
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[1]->title
            ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();
        $response = $this->getJson(route('books.show', $book));
        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    public function can_create_books()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');
        $this->postJson(route('books.store'), [
            'title' => 'prueba testing create'
        ])->assertJsonFragment([
            'title' => 'prueba testing create'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'prueba testing create'
        ]);
    }

    /** @test */
    public function can_update_books()
    {
        $book = Book::factory()->create();
        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');
        $this->patchJson(route('books.update', $book), [
            'title' => 'prueba edicion'
        ])->assertJsonFragment([
            'title' => 'prueba edicion'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'prueba edicion'
        ]);
    }

    /** @test */
    public function can_delete_books()
    {
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy', $book))
        ->assertNoContent();
        $this->assertDatabaseCount('books', 0);
    }


}
