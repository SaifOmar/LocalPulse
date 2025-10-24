<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Arr;

class CreateUserAction
{
    protected array $data;

    public function extractFromRequest($request)
    {
        $this->data = Arr::only(
            $request->toArray(),
            ['first_name', 'last_name', 'email', 'longitude', 'latitude', 'accuracy_meters', 'country', 'city']
        );
        return $this;
    }

    public function create()
    {
        return User::create($this->data);
    }
}
