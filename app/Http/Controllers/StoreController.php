<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreForm;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render("Stores/Index", [
            "stores" => Store::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render("Stores/CreateEdit");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreForm $request)
    {
        $store = Store::create($request->validated());

        return response()->json($store, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        return Inertia::render("Stores/CreateEdit", [
            "storeEdit" => $store
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $store->update($request->validated());

        return response()->json($store, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return response()->json("OK", 200);
    }

    /**
     * Lists the Users in that Store
     * @param \App\Models\Store $store
     * @return \Illuminate\Http\Response
     */
    public function listUsers(Store $store)
    {
        $userList = User::all()->merge($store->users);
        return Inertia::render("Stores/Users", [
            "userList" => $userList,
            "store" => $store,
        ]);
    }

    /**
     * Updates Store Users
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Store $store
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request, Store $store)
    {
        $users = collect($request->users)->pluck("id");

        $store->users()->sync($users);

        return response()->json("OK");
    }
}
