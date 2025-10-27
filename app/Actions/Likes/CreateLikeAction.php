<?php

namespace App\Actions\Likes;

use App\Helpers\Helpers;
use App\Enums\LikedTypeEnum;
use App\Models\Like;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Log;

class CreateLikeAction
{
    public array $data;

    public function store(): Like
    {
        $this->enforceSqlIntegrity();
        return Like::firstOrCreate($this->data, $this->data);
    }


    public function extractFromRequest($request): CreateLikeAction
    {
        $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
        $this->data = [
            ...Arr::only(
                $request->toArray(),
                ['liked_id', 'liked_type']
            ), "account_id" => $account->id];
        return $this;
    }
    private function enforceSqlIntegrity(): CreateLikeAction
    {
        try {
            $table = $this->data['liked_type'] . "s";
            $id = $this->data['liked_id'];
            DB::table($table)->where('id', $id)->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception("Could not find the liked object");
        }
        return $this;
    }

}
