<?php

namespace App\Http\Resources;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function __construct(User $user, public Account $account, public string $token)
    {
        parent::__construct($user);
    }
    public function toArray(Request $request): array
    {
        $arr = new AccountResource($this->account)->toArray($request);
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'access' => $this->token,
            ...$arr
        ];
    }
}
