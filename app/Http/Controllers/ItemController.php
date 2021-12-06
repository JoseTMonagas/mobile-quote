<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemForm;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $context = [
            'devices' => Item::whereNull("sold")->get(),
        ];

        return Inertia::render('Items/Index', $context);
    }

    /**
     * Return a list of resources
     *
     * @return \Illuminate\Http\Response
     */
    public function list(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Item::whereNull("sold")->get());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {

        return Inertia::render("Items/CreateEdit");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ItemForm $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemForm $request): \Illuminate\Http\JsonResponse
    {
        $items = $request->validated();
        $created = [];
        foreach ($items["items"] as $item) {
            $created[] = Item::create($item);
        }
        return response()->json($created, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Inertia\Response
     */
    public function edit(Item $item)
    {
        return Inertia::render('Items/CreateEdit', [
            "item" => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ItemForm  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemForm $request, Item $item)
    {
        if ($item->update($request->validated())) {
            return response()->json($item, 200);
        }
        return response()->json('ERROR', 500);
    }
}
