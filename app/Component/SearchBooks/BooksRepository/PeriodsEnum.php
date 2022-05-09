<?php
declare(strict_types=1);

namespace App\Component\SearchBooks\BooksRepository;

class PeriodsEnum
{
    const MAX_5_YEARS = 'max-5-years';
    const MAX_10_YEARS = 'max-10-years';
    const MORE_THEN_10_YEARS = 'more-then-10-years';

    public static $types = [self::MAX_5_YEARS, self::MAX_10_YEARS, self::MORE_THEN_10_YEARS];
}
