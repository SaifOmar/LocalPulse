<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $accounts = $this->accounts;
        $arr= [];
        foreach ($accounts as $account) {
            $arr[] = route('accounts.show', $account->id);
        }
        return [
            'data'  =>  [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'city' => $this->city,
                'handle' => $this->handle,
                'avatar' => $this->avatar?->url ?? null,
                'country' => $this->country,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'links' => [
                    'accounts'=> [
                        ...$arr
                    ],
                ]
            ]

        ];
    }
}
