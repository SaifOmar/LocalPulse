<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\StoreLikeRequest;
use App\Models\Like;
use Illuminate\Validation\ValidationException;

class LikeController extends Controller
{
    public function store(StoreLikeRequest $request)
    {
        try {
            $data = $request->validated();
            $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
            if (Like::where(['account_id' => $account->id, 'pulse_id' => $data['pulse_id'], 'type' => $data['type']])->first()) {
                // or maube redirect ?
                return response()->json(['message' => 'already liked'], 422);
            }
            $like = Like::create($data);
            return response()->json($like)->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function destroy(Like $like)
    {
        try {
            $like->delete();
            return response()->json()->setStatusCode(200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }

}
