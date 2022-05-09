<?php
declare(strict_types=1);

namespace App\Component\ImportBooks;

use App\Http\Controller;
use Illuminate\Http\Request;

class ImportBooksController extends Controller
{
    public function __construct(
        protected ImportBooksService $importBooksService
    ){
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv']
        ]);

        $this->importBooksService->import(
            $data['file']->getPathname()
        );

        return response()->json([
            'success' => 'OK',
            'message' => 'File successfully imported.'
        ]);
    }
}
