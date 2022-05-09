<?php
declare(strict_types=1);

namespace App\Component\Auth;

use App\Http\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @see https://laravel.com/docs/5.8/api-authentication
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'success' => 'OK',
                'token' => User::findOrFail(Auth::id())->api_token
            ]);
        }

        return response()->json(
            ['message' => 'Wrong credentials provided.'],
            422
        );
    }
}
