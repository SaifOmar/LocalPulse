<?php

namespace App\Http\Controllers\Likes;

use App\Actions\Likes\CreateLikeAction;
use App\Helpers\Helpers;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\DeleteLikeRequest;
use App\Jobs\ProcessInteraction;
use App\Models\Like;
use App\Models\Pulse;
use App\Actions\Likes\DeleteLikeAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

use function request;
use function ucfirst;

//TODO: now find a good way to actually do this because this is just pure shit rn
class LikeController extends Controller
{
    // this is for testing only
    public function index()
    {
        $likes = Like::all();
        return  response()->json($likes);
    }
    // this should be a job and when the job returns then I should prcocess the interaction
    public function store(StoreLikeRequest $request, CreateLikeAction $action)
    {
        try {
            $obj = $action->extractFromRequest($request);
            $like = $obj->store();
            // if (Like::where(['account_id' => $account->id, 'pulse_id' => $data['pulse_id'], 'type' => $data['type']])->first()) {
            //     return response()->json(['message' => 'already liked'], 422);
            // }

            // $meta = [
            //     'liked_by' => $account->id,
            //     'creation_like_count' => Pulse::find($data['pulse_id'])->likes()->count(),
            // ];
            //
            // ProcessInteraction::dispatch(
            //     Helpers::generateInteractionData($account, $data['pulse_id'], 'like', $meta)
            // );
            return response()->json()->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function destroy(DeleteLikeRequest $request, DeleteLikeAction $action)
    {
        try {
            $obj = $action->extractFromRequest($request);
            if ($obj->destroy() != true) {
                return response()->json(['error' => "deletion failed"])->setStatusCode(400);
            }
            // $account = $request->user()->getActiveAccount();
            // $typeName_id = Helpers::getTypeId($request->type);
            // $type = "App\Models\\".ucfirst(str_replace('_like', '', $request->type));
            // if (!$this->check($request->type_id, $request->type)) {
            //     return response()->json(['error' => "invalid data"], 401);
            // }
            // $like = Like::where([
            //     'account_id' => $account->id,
            //     'type' => $request->type,
            //     $typeName_id => $request->type_id,
            // ])->first();
            // $meta = [
            //     'disliked_by' => $account->id,
            //     'creation_like_count' => Pulse::find($request->type_id)->likes()->count(),
            // ];
            // ProcessInteraction::dispatch(
            //     Helpers::generateInteractionData($account, $request->pulse_id, 'like', $meta)
            // );
            return response()->json()->noContent();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
    // private function check($type_id, $type)
    // {
    //     $type = "App\Models\\".ucfirst(str_replace('_like', '', $type));
    //     return $type::find($type_id);
    // }
}
