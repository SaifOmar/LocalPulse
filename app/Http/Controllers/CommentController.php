<?php

namespace App\Http\Controllers;

use App\Models\Pulse;
use App\Models\Comment;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

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
            $comment = Comment::create($data);
            return response()->json($data)->setStatusCode(201);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(
                ['message' => $e->getMessage()]
            );
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
        try {
            $data = $request->validated();
            $comment->update($data);
            return response()->json($comment);
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
