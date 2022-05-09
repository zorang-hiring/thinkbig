<?php
declare(strict_types=1);

namespace App\Component\SearchBooks;

use App\Component\SearchBooks\BooksRepository\PeriodsEnum;
use App\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class BooksController extends Controller
{
    public function __construct(
        protected BooksRepository $repository
    ){}

    public function list(Request $request)
    {
        return response()->json([
            'success' => 'OK',
            'result' => BookResource::collection(
                $this->findBooks($request)
            )
        ]);
    }

    protected function buildSearchCriteria(Request $request): array
    {
        $request->validate([
            'period' => Rule::in(PeriodsEnum::$types),
        ]);

        $criteria = [];
        if ($param = $request->get('name')) {
            $criteria['searchName'] = $param;
        }
        if ($param = $request->get('period')) {
            $criteria['period'] = $param;
        }
        return $criteria;
    }

    protected function findBooks(Request $request): Collection
    {
        return $this->repository->findByCriteria(
            $this->buildSearchCriteria($request)
        );
    }
}
