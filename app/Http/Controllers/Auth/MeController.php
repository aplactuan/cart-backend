<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\PrivateUserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return PrivateUserResource
     */
    public function __invoke(Request $request)
    {
        return new PrivateUserResource($request->user());
    }
}
