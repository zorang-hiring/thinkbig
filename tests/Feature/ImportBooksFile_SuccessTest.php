<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportBooksFile_SuccessTest extends AbstractTestCase
{
    use RefreshDatabase;

    public function testImport()
    {
        // GIVEN

        $token = $this->createAdminAndGetApiToken();

        // WHEN

        $response = $this->postImportBooks(
            $token,
            [ // CSV file content:
                'Naziv Knjige,Autor,Izdavac,Godina Izdanja',
                '"Book, A","Author1, Author2","PublisherX",21/05/2004',
                'Book B,"Author3","PublisherX",22/05/2004',
                'Book C,"Author2","PublisherX",21/06/2004',
                'Book D,"Author3, Author4",Publisher Y,21/12/1998',
                'Book E,Author 5,Publisher Y,21/12/1999',
                'Book F,Author4,PublisherX,19/10/1995',
                'Book F,Author 5,Publisher Y,20/11/1996',
            ]
        );

        // THEN

        $response->assertStatus(200);
        $response->assertExactJson([
            'success' => 'OK',
            'message' => 'File successfully imported.'
        ]);
        $this->assertDatabaseCount('publishers', 2);
        $this->assertDatabaseHas('publishers', ['name' => 'PublisherX']);
        $this->assertDatabaseHas('publishers', ['name' => 'Publisher Y']);
        $this->assertDatabaseCount('authors', 5);
        $this->assertDatabaseHas('authors', ['name' => 'Author1']);
        $this->assertDatabaseHas('authors', ['name' => 'Author2']);
        $this->assertDatabaseHas('authors', ['name' => 'Author3']);
        $this->assertDatabaseHas('authors', ['name' => 'Author4']);
        $this->assertDatabaseHas('authors', ['name' => 'Author 5']);
        $this->assertDatabaseCount('books', 6);
        $this->assertDatabaseHas('books', [
            'name' => 'Book, A',
            'publisher_id' => 1,
            'publication_date' => '2004-05-21'
        ]);
        $this->assertDatabaseHas('books', [
            'name' => 'Book B',
            'publisher_id' => 1,
            'publication_date' => '2004-05-22'
        ]);
        $this->assertDatabaseHas('books', [
            'name' => 'Book C',
            'publisher_id' => 1,
            'publication_date' => '2004-06-21'
        ]);
        $this->assertDatabaseHas('books', [
            'name' => 'Book D',
            'publisher_id' => 2,
            'publication_date' => '1998-12-21'
        ]);
        $this->assertDatabaseHas('books', [
            'name' => 'Book E',
            'publisher_id' => 2,
            'publication_date' => '1999-12-21'
        ]);
        $this->assertDatabaseHas('books', [
            'name' => 'Book F',
            'publisher_id' => 2,
            'publication_date' => '1996-11-20'
        ]);
        $this->assertDatabaseCount('authors_has_books', 8);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 1, 'author_id' => 1]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 1, 'author_id' => 2]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 2, 'author_id' => 3]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 3, 'author_id' => 2]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 4, 'author_id' => 3]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 4, 'author_id' => 4]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 5, 'author_id' => 5]);
        $this->assertDatabaseHas('authors_has_books', ['book_id' => 6, 'author_id' => 5]);
    }
}
