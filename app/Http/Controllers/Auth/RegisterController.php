<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\PrivateUserResource;
use App\Models\User;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): PrivateUserResource
    {
        $validated = $request->validated();
        $user = User::create($validated);

        return new PrivateUserResource($user);
    }
}
