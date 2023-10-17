<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Achievement;
use App\Models\User;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
    public function delete($comment_id)
    {
        if(Auth::user()->profile->role != 'student' || Auth::user()->id == $item->user_id || Auth::user()->id == $achievement->user_id) {
            $comment = Comment::find($comment_id);
            if(!$comment) {
                return back()->with('error', "Comment doesn't exist");
            }
            $comment->delete();
            return back()->with('success', 'Comment removed');
        }
        return back()->with('error', 'Unauthorized');
    }
}
