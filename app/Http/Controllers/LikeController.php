<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string',
            'likeable_id' => 'required|integer'
        ]);

        $like = Like::create([
            'user_id' => auth()->id(),
            'likeable_type' => $request->likeable_type,
            'likeable_id' => $request->likeable_id,
        ]);

        return response()->json($like, 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string',
            'likeable_id' => 'required|integer'
        ]);

        $like = Like::where([
            'user_id' => auth()->id(),
            'likeable_type' => $request->likeable_type,
            'likeable_id' => $request->likeable_id
        ])->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like deleted successfully'], 204);
        }

        return response()->json(['message' => 'Like not found'], 404);
    }
}
