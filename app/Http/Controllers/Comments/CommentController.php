<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInteraction;
use App\Models\Pulse;
use App\Models\Comment;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Helpers\Helpers;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Pulse $pulse)
    {
        try {
            $comments = $pulse->comments;
            return response()->json($comments);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(
                ['message' => $e->getMessage()]
            );
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        try {
            $data = $request->validated();
            $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
            $comment = Comment::create($data);
            $meta = [
                'commented_by' => $account->id,
                'creation_comment_count' => Pulse::find($data['pulse_id'])->comments()->count(),
            ];
            ProcessInteraction::dispatch(
                Helpers::generateInteractionData($account, $data['pulse_id'], 'comment', $meta)
            );
            return response()->json($comment->toArray())->setStatusCode(201);

        } catch (\Exception $e) {
            throw ValidationException::withMessages(
                ['message' => $e->getMessage()]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
        try {
            $account = Helpers::getUserAuthAccount($request->user()->currentAccessToken()->name);
            $data = $request->validated();
            $comment->update($data);
            $meta = [
                'commented_by' => $account->id,
                'creation_comment_count' => Pulse::find($data['pulse_id'])->comments()->count(),
                'updated_at' => now(),
            ];
            ProcessInteraction::dispatch(
                Helpers::generateInteractionData($account, $data['pulse_id'], 'comment', $meta)
            );
            return response()->json($comment->toArray())->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(
                ['message' => $e->getMessage()]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return response()->json()->setStatusCode(200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(
                ['message' => $e->getMessage()]
            );
        }
        //
    }
}
