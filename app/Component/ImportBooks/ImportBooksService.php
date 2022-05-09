<?php
declare(strict_types=1);

namespace App\Component\ImportBooks;

use App\Component\ImportBooks\ImportBooksService\ImportBooksRawFileParser;
use App\Component\ImportBooks\ImportBooksService\ImportBooksRawFileParserCsv;
use App\Component\ImportBooks\ImportBooksService\ImportBooksRawLineDto;
use App\Component\ImportBooks\ImportBooksService\ImportBooksStorageService;

class ImportBooksService
{
    /**
     * @var ImportBooksRawFileParser[]
     */
    protected array $rawParsers;

    public function __construct(
        protected ImportBooksStorageService $storage,
    ){}

    public function import(string $filePath)
    {
        foreach ($this->parse($filePath) as $rawLine) {
            $this->storage->store($rawLine);
        }
    }

    public function addRawParser(ImportBooksRawFileParser $parser)
    {
        $this->rawParsers[] = $parser;
    }

    /**
     * @param string $filePath
     * @return ImportBooksRawLineDto[]
     */
    protected function parse(string $filePath): array
    {
        $rawLines = [];
        foreach ($this->rawParsers as $parser) {
            if ($parser->getType() === $this->getContentType($filePath)) {
                $rawLines = $parser->parse($filePath);
            }
        }
        return $rawLines;
    }

    private function getContentType(string $filePath): string|false
    {
        return mime_content_type($filePath);
    }
}
