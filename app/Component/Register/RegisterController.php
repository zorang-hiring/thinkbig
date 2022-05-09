<?php
declare(strict_types=1);

namespace App\Component\Register;

use App\Http\Controller;
use App\Models\User;
use App\Enum\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:7', 'max:255'],
        ]);

        User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
            'api_token' => Str::random(60),
            'roles' => [UserRole::ROLE_MEMBER]
        ]);

        return response(null, 201);
    }
}
