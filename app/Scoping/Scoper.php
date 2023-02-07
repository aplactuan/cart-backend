<?php

namespace App\Scoping;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Scoper
{
    public function __construct(protected Request $request)
    {

    }

    public function apply(Builder $query, array $scopes)
    {
        foreach ($this->limitScopes($scopes) as $key => $scope) {
            foreach ($scopes as $scope) {
                if (!($scope instanceof Scope)) {
                    continue;
                }
                $scope->apply($query, $this->request->get($key));
            }
        }

        return $query;
    }

    protected function limitScopes(array $scopes): array
    {
        return Arr::only(
            $scopes,
            array_keys($this->request->all())
        );
    }
}
