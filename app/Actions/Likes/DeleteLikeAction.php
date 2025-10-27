<?php

namespace App\Actions\Likes;

use App\Models\Like;
use App\Helpers\Helpers;

class DeleteLikeAction
{
    protected Like $like;

    public function destroy(): bool|null
    {
        return $this->like->delete();
    }

    public function extractFromRequest($request): DeleteLikeAction
    {
        try {
            $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
            $this->like = Like::where([
                'account_id' => $account->id,
                'liked_type' => $request->liked_type,
                "liked_id" => $request->liked_id,
                ])->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception("Could not find the liked object");
        }
        return $this;
    }
}
