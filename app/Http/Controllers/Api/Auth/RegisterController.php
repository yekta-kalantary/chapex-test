<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utility\ApiResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $data['email'] = time().$data['email'];
        $user = User::create($data);
        $token = $user->createToken('auth_token')->accessToken;

        return ApiResponse::handle([
            'token' => $token,
        ]);
    }
}
