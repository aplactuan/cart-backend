<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\PrivateUserResource;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'errors' => [
                    'email' => [
                        'Invalid Credentials'
                    ]
                ]
            ], 422);
        }

        $token = auth()->user()->createToken('cart')->accessToken;

        return (new PrivateUserResource($request->user()))
            ->additional([
                'meta' => [
                    'token' => $token
                ]
            ]);
    }
}
