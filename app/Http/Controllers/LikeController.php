<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\StoreLikeRequest;
use App\Jobs\ProcessInteraction;
use App\Models\Like;
use App\Models\Pulse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LikeController extends Controller
{
    // this should be a job and when the job returns then I should prcocess the interaction
    public function store(StoreLikeRequest $request)
    {
        try {
            $data = $request->validated();
            $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
            if (Like::where(['account_id' => $account->id, 'pulse_id' => $data['pulse_id'], 'type' => $data['type']])->first()) {
                return response()->json(['message' => 'already liked'], 422);
            }
            $like = Like::create($data);
            $meta = [
                'liked_by' => $account->id,
                'creation_like_count' => Pulse::find($data['pulse_id'])->likes()->count(),
            ];
            ProcessInteraction::dispatch(
                Helpers::generateInteractionData($account, $data['pulse_id'], 'like', $meta)
            );
            return response()->json($like)->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function destroy(Request $request)
    {
        $account = $request->user()->getActiveAccount();
        $like = Like::where([
            'account_id' => $account->id,
            'pulse_id' => $request->pulse_id,
            'type' => $request->type,
        ])->first();
        try {
            if ($like && $like->delete()) {
                $meta = [
                    'disliked_by' => $account->id,
                    'creation_like_count' => Pulse::find($request->pulse_id)->likes()->count(),
                ];
                ProcessInteraction::dispatch(
                    Helpers::generateInteractionData($account, $request->pulse_id, 'like', $meta)
                );
                return response()->json()->setStatusCode(200);
            }

            return response()->json(['error' => "deletion failed"])->setStatusCode(400);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }

}
