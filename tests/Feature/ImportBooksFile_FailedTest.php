<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class ImportBooksFile_FailedTest extends AbstractTestCase
{
    use RefreshDatabase;

    public function test_not_authenticated()
    {
        $response = $this->post('/api/import-books');

        $response->assertStatus(403);
    }

    public function test_not_admin()
    {
        $token = 'a_member_token';

        User::create([
            'name' => 'Name',
            'email' => 'user@gmail.com',
            'password' => bcrypt('pass123'),
            'api_token' => $token,
            'roles' => [UserRole::ROLE_MEMBER]
        ]);

        $response = $this->post('/api/import-books', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(403);
    }

    public function dataProvider_invalid_data(): array
    {
        return [
            [
                'postData' => [],
                'expectedResponse' => [
                    "message" => "The file field is required.",
                    "errors" => [
                        "file" => ["The file field is required."]
                    ]
                ]
            ],
            [
                'postData' => [
                    'file' => UploadedFile::fake()->create(
                        'document.pdf', 255, 'application/pdf'
                    )
                ],
                'expectedResponse' => [
                    "message" => "The file must be a file of type: csv.",
                    "errors" => [
                        "file" => ["The file must be a file of type: csv."]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProvider_invalid_data
     */
    public function test_invalid_data(array $postData, $expectedResponse)
    {
        // GIVEN
        $token = $this->createAdminAndGetApiToken();

        // WHEN

        $response = $this->post('/api/import-books',
            $postData,
            ['Authorization' => 'Bearer ' . $token]
        );

        // THEN

        $response->assertStatus(422);
        $response->assertExactJson($expectedResponse);
    }
}
