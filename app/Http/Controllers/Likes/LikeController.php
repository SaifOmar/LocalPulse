<?php

namespace App\Http\Controllers\Likes;

use App\Actions\Likes\CreateLikeAction;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\DeleteLikeRequest;
use App\Models\Like;
use App\Actions\Likes\DeleteLikeAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;


class LikeController extends Controller
{
    // this is for testing only
    public function index(): JsonResponse
    {
        $likes = Like::all();
        return  response()->json($likes);
    }
    // this should be a job and when the job returns then I should prcocess the interaction
    public function store(StoreLikeRequest $request, CreateLikeAction $action): JsonResponse
    {

        try {
            $obj = $action->extractFromRequest($request);
            $like = $obj->store();
            return response()->json()->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function destroy(DeleteLikeRequest $request, DeleteLikeAction $action): JsonResponse|Response
    {
        try {
            $obj = $action->extractFromRequest($request);
            if ($obj->destroy() != true) {
                return response()->json(['error' => "deletion failed"])->setStatusCode(400);
            }
            return response()->noContent();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
