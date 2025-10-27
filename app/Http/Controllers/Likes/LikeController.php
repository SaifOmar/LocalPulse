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
use Illuminate\Support\Facades\DB;

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
            return response()->noContent();
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
