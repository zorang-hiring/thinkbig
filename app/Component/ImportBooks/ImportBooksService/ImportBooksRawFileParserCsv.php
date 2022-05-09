<?php
declare(strict_types=1);

namespace App\Component\ImportBooks\ImportBooksService;

use DateTime;

class ImportBooksRawFileParserCsv implements ImportBooksRawFileParser
{
    public function getType(): string
    {
        return 'application/csv';
    }

    /**
     * @param string $filePath
     * @return ImportBooksRawLineDto[]
     */
    public function parse(string $filePath): array
    {
        $return = [];

        if (($open = fopen($filePath, "r")) === false) {
            return $return;
        }

        $index = 0;
        while (($data = fgetcsv($open, 0, ",")) !== false) {
            if ($index > 0) { // skip csv header line
                $return[] = $this->parseRawLine($data);
            }
            $index += 1;
        }
        fclose($open);

        return $return;
    }

    protected function parseRawLine(array $data): ImportBooksRawLineDto
    {
        $dto = new ImportBooksRawLineDto();
        $dto->book = trim($data[0]);
        $dto->authors = array_map(
            function ($author) {
                return trim($author);
            },
            explode(',', $data[1])
        );
        $dto->publisher = trim($data[2]);
        $dto->publishingDate = DateTime::createFromFormat('d/m/Y', trim($data[3]));
        return $dto;
    }
}
