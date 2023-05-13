<?php

namespace App\Http\Controllers\Countries;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryIndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     */
    public function __invoke(Request $request)
    {
        return CountryResource::collection(Country::query()
            ->orderBy('name')
            ->get()
        );
    }
}
