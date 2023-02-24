<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function action(RegisterRequest $request): PrivateUserResource
    {
        $validated = $request->safe()->only('name', 'email', 'password');
        $user = User::create($validated);

        return new PrivateUserResource($user);
    }
}
