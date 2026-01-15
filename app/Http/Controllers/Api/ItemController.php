<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Item::when($request->status, fn ($q) =>$q->where('status', $request->status))->get();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
        'name' => 'required',
        'code' => 'required|unique:items',
        'description' => 'nullable',
        'status' => 'required|in:active,inactive'
        ]);


        return Item::create($data);
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
    public function update(Request $request, Item $item)
    {
        $item->update($request->validate([
        'name' => 'required',
        'description' => 'nullable',
        'status' => 'required|in:active,inactive',
        'code' => 'required'
        ]));


        return $item;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Item deleted successfully'
        ], 200);
    }
}
