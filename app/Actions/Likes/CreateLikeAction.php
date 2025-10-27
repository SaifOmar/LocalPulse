<?php

namespace App\Actions\Likes;

use App\Helpers\Helpers;
use App\Enums\LikedTypeEnum;
use App\Models\Like;
use Illuminate\Support\Arr;

class CreateLikeAction
{
    protected array $data;

    public function store(): Like
    {
        return Like::createOrFirst($this->data, $this->data);
    }

    public function extractFromRequest($request): CreateLikeAction
    {
        $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
        $this->data = [
            ...Arr::only(
                $request->toArray(),
                ['liked_id', 'liked_type']
            ), $account->id];
        return $this;
    }

}
