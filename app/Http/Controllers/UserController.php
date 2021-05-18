<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == "ADMIN") {
            $store = Auth::user()->stores->first();
            $users = User::whereHas("stores", function ($query) use ($store) {
                return $query->where("stores.id", $store->id);
            })->get();
        } else {
            $users = User::all();
        }

        return Inertia::render('Users/Index', [
            "users" => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Users/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserForm $request)
    {
        $form = $request->validated();
        $user = User::create([
            "name" => $form["name"],
            "email" => $form["email"],
            "password" => Hash::make($form["password"]),
        ]);

        if (Auth::user()->role == "ADMIN") {
            $stores = Auth::user()->stores->pluck("id");
            $user->stores->attach($stores);
        }
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/CreateEdit', [
            "userEdit" => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserForm $request, User $user)
    {
        $form = $request->validated();
        $form["password"] = Hash::make($form["password"]);
        if ($user->update($form)) {
            return response()->json($user, 200);
        } else {
            return response()->json('', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->stores()->detach();
        if ($user->delete()) {
            return response()->json('OK', 200);
        } else {
            return response()->json('', 500);
        }
    }

    public function changeRole(User $user)
    {
        return Inertia::render('Users/Roles', [
            "userEdit" => $user
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $newRole = $request->role;
        $user->role = $newRole;
        $user->save();
        return response()->json("OK");
    }

    public function updateStore(Request $request, User $user)
    {
    }
}
