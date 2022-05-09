<?php

namespace App\Providers;

use App\Component\ImportBooks\ImportBooksService;
use App\Component\ImportBooks\ImportBooksService\ImportBooksRawFileParserCsv;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ImportBooksServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            ImportBooksService::class,
            function ($app) {
                $service = new ImportBooksService(
                    new ImportBooksService\ImportBooksStorageService()
                );
                $service->addRawParser(new ImportBooksRawFileParserCsv());
                return $service;
            }
        );
    }
}
