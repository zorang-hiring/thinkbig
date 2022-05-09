<?php
declare(strict_types=1);

namespace App\Component\SearchBooks\BooksRepository;

use Carbon\Carbon;

class PeriodCriteriaMax10Years implements PeriodCriteria
{
    public function getName(): string
    {
        return PeriodsEnum::MAX_10_YEARS;
    }

    public function getPeriodOperator(): string
    {
        return '>=';
    }

    public function getPeriodValue(): int
    {
        return Carbon::now()->format('Y') - 10;
    }
}
