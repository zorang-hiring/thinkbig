<?php
declare(strict_types=1);

namespace App\Component\ImportBooks\ImportBooksService;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;

class ImportBooksStorageService
{
    public function store(ImportBooksRawLineDto $rawFileLine): void
    {
        $publisher = $this->storePublisher($rawFileLine->publisher);
        $book = $this->storeBook($rawFileLine, $publisher);
        $this->storeAuthors($rawFileLine->authors, $book);

    }

    protected function storePublisher(string $publisher): Publisher
    {
        return Publisher::firstOrCreate(['name' => $publisher]);
    }

    protected function storeBook(ImportBooksRawLineDto $rawFileLine, Publisher $publisher): Book
    {
        if (!$book = Book::firstWhere(['name' => $rawFileLine->book])) {
            // add new book
            $book = Book::create([
                'name' => $rawFileLine->book,
                'publication_date' => $rawFileLine->publishingDate,
                'publisher_id' => $publisher->id
            ]);
        } else {
            // update existing book
            $book->publication_date = $rawFileLine->publishingDate;
            $book->publisher_id = $publisher->id;
            $book->save();
        }
        return $book;
    }

    protected function storeAuthors(array $authors, Book $book): void
    {
        // clear authors if already added
        $book->authors()->detach();
        // add authors
        foreach ($authors as $author) {
            $book->authors()->attach(
                Author::firstOrCreate(['name' => $author])
            );
            $book->save();
        }
    }
}
