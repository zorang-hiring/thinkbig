<?php
declare(strict_types=1);

namespace App\Component\SearchBooks\BooksRepository;

interface PeriodCriteria
{
    public function getName(): string;
    public function getPeriodOperator(): string;
    public function getPeriodValue(): int;
}
