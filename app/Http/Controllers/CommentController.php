<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer'
        ]);

        $comments = Comment::where('commentable_type', $request->commentable_type)
            ->where('commentable_id', $request->commentable_id)
            ->with('user')
            ->get();

        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'content' => 'required',
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer'
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'commentable_type' => $request->commentable_type,
            'commentable_id' => $request->commentable_id,
        ]);

        return response()->json($comment, 201);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 204);
    }
}
