<?php
declare(strict_types=1);

namespace App\Component\ImportBooks\ImportBooksService;

use DateTimeInterface;

class ImportBooksRawLineDto
{
    public string $book;
    public array $authors;
    public string $publisher;
    public DateTimeInterface $publishingDate;
}
