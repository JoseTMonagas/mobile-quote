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
            'items' => Item::whereNull("sold")->get(),
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
    public function edit($item): \Inertia\Response
    {
        $ids = base64_decode(urldecode($item));
        $ids = explode(";", $ids);
        $items = Item::whereIn("id", $ids)->get();
        return Inertia::render('Items/CreateEdit', [
            "editing" => $items
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $items = $request->input("items");

        $updated = [];
        foreach ($items as $item) {
            $object = Item::find($item["id"]);
            $object->update($item);
            $updated[] = $object;
        }

        return response()->json($updated);
    }

    /**
     * Deletes multiple resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obliterate(Request $request)
    {
        $items = collect($request->input())->pluck("id");
        $deleted = Item::destroy($items->toArray());
        return response()->json($deleted);
    }

    public function correct(Request $request)
    {
        $ids = collect($request->input())->toArray();
        $items = Item::whereIn("id", $ids)->get();
        return Inertia::render('Items/CreateEdit', [
            "editing" => $items
        ]);
    }
}
