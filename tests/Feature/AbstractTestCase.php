<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function createAdminAndGetApiToken(): string
    {
        $token = 'an_admin_token';

        User::create([
            'name' => 'Name',
            'email' => 'user@gmail.com',
            'password' => bcrypt('pass123'),
            'api_token' => $token,
            'roles' => [UserRole::ROLE_ADMIN]
        ]);

        return $token;
    }

    protected function postImportBooks(string $apiToken, array $csvLines): TestResponse
    {
        $response = $this->post('/api/import-books',
            [
                'file' => UploadedFile::fake()->createWithContent(
                    'test.csv', implode("\n", $csvLines)
                )
            ],
            ['Authorization' => 'Bearer ' . $apiToken]
        );

        return $response;
    }
}
