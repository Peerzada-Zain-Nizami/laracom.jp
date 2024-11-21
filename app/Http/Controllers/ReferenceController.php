<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $references = Reference::with(['category', 'tutorial'])->get();
        return response()->json($references);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tutorial_id' => 'required|exists:tutorials,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $reference = Reference::create($validated);
        return response()->json($reference, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reference = Reference::with(['category', 'tutorial'])->findOrFail($id);
        return response()->json($reference);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'tutorial_id' => 'sometimes|exists:tutorials,id',
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
        ]);

        $reference->update($validated);
        return response()->json($reference);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reference = Reference::findOrFail($id);
        $reference->delete();

        return response()->json(['message' => 'Reference deleted successfully']);
    }
}
