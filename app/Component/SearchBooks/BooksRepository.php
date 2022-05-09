<?php
declare(strict_types=1);

namespace App\Component\SearchBooks;

use App\Component\SearchBooks\BooksRepository\PeriodCriteria;
use App\Component\SearchBooks\BooksRepository\PeriodCriteriaMax10Years;
use App\Component\SearchBooks\BooksRepository\PeriodCriteriaMax5Years;
use App\Component\SearchBooks\BooksRepository\PeriodCriteriaMoreThen10Years;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BooksRepository
{
    /**
     * @var PeriodCriteria[]
     */
    protected array $periodCriterias;

    public function __construct()
    {
        $this->addPeriodCriteria(new PeriodCriteriaMax5Years());
        $this->addPeriodCriteria(new PeriodCriteriaMax10Years());
        $this->addPeriodCriteria(new PeriodCriteriaMoreThen10Years());
    }

    public function findByCriteria(array $criteria)
    {
        $query = Book::query();

        if (isset($criteria['searchName'])) {
            $query->where(
                'name',
                'like',
                '%' . $criteria['searchName'] . '%'
            );
        }

        if (isset($criteria['period'])) {
            foreach ($this->periodCriterias as $periodCriteria) {
                if ($periodCriteria->getName() === $criteria['period'])
                $query->where(
                    DB::raw('YEAR(publication_date)'),
                    $periodCriteria->getPeriodOperator(),
                    $periodCriteria->getPeriodValue()
                );
            }
        }

        return $query->get();
    }

    protected function addPeriodCriteria(PeriodCriteria $criteria)
    {
        $this->periodCriterias[] = $criteria;
    }
}
