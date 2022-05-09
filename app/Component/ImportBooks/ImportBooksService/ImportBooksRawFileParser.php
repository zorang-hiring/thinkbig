<?php
declare(strict_types=1);

namespace App\Component\ImportBooks\ImportBooksService;

interface ImportBooksRawFileParser
{
    /**
     * Return parser type e.g. CSV type
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $filePath
     * @return ImportBooksRawLineDto[]
     */
    public function parse(string $filePath): array;
}
