<?php
declare(strict_types=1);

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchBooksTest extends AbstractTestCase
{
    use RefreshDatabase;

    public function dataProvider_testSearch(): array
    {
        // todo add more data to response
        return [
            [
                'getParams' => [],
                'expectedResult' => [
                    [
                        'name' => '11',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2022-05-09'
                    ],
                    [
                        'name' => '1abc1',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1", "Author2"],
                        'published' => '2017-05-09'
                    ],
                    [
                        'name' => '22',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2012-05-09'
                    ],
                    [
                        'name' => '2abc2',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2002-05-09'
                    ],
                    [
                        'name' => '33',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2000-05-09'
                    ]
                ]
            ],
            [
                'getParams' => [
                    'name' => 'abc'
                ],
                'expectedResult' => [
                    [
                        'name' => '1abc1',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1", "Author2"],
                        'published' => '2017-05-09'
                    ],
                    [
                        'name' => '2abc2',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2002-05-09'
                    ]
                ]
            ],
            [
                'getParams' => [
                    'period' => 'max-5-years'
                ],
                'expectedResult' => [
                    [
                        'name' => '11',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2022-05-09'
                    ],
                    [
                        'name' => '1abc1',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1", "Author2"],
                        'published' => '2017-05-09'
                    ]
                ]
            ],
            [
                'getParams' => [
                    'period' => 'max-10-years'
                ],
                'expectedResult' => [
                    [
                        'name' => '11',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2022-05-09'
                    ],
                    [
                        'name' => '1abc1',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1", "Author2"],
                        'published' => '2017-05-09'
                    ],
                    [
                        'name' => '22',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2012-05-09'
                    ]
                ]
            ],
            [
                'getParams' => [
                    'period' => 'more-then-10-years'
                ],
                'expectedResult' => [
                    [
                        'name' => '2abc2',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2002-05-09'
                    ],
                    [
                        'name' => '33',
                        'publisher' => 'PublisherX',
                        'authors' => ["Author1"],
                        'published' => '2000-05-09'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider_testSearch
     */
    public function testSearch(array $getParams, array $expectedResult)
    {
        // GIVEN
        Carbon::setTestNow('2022-05-09 00:00:00');

        $this->postImportBooks(
            $this->createAdminAndGetApiToken(),
            [
                // CSV file content:
                'Naziv Knjige,Autor,Izdavac,Godina Izdanja',
                '11,Author1,PublisherX,09/05/2022',
                '1abc1,"Author1,Author2",PublisherX,09/05/2017',
                '22,Author1,PublisherX,09/05/2012',
                '2abc2,Author1,PublisherX,09/05/2002',
                '33,Author1,PublisherX,09/05/2000',
            ]
        );

        // WHEN
        $response = $this->get('/api/books?' . http_build_query($getParams));

        // THEN
        $response->assertStatus(200);
        $response->assertExactJson([
            'success' => 'OK',
            'result' => $expectedResult
        ]);
    }
}
