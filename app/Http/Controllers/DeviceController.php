<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceForm;
use App\Models\Device;
use App\Models\DeviceStorePrice;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Devices/Index', [
            'devices' => Device::all()->append("custom_price", "store_price")->toArray()
        ]);
    }

    /**
     * Return a list of the resources
     * 
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return response()->json(Device::all()->append("custom_price", "store_price")->load('issues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Devices/CreateEdit', [
            'issueList' => Issue::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\DeviceForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceForm $request)
    {
        $image = $request->file('image')->store('devices', 'public');
        $formData = $request->validated();
        $formData["image"] = $image;
        $device = Device::create($formData);
        return response()->json($device, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        return Inertia::render('Devices/CreateEdit', [
            "device" => $device,
            'issueList' => Issue::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\DeviceForm  $request
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceForm $request, Device $device)
    {
        $formData = $request->validated();
        if ($request->has("image")) {
            $image = $request->file('image')->store('devices', 'public');
            $formData["image"] = $image;
        } else {
            unset($formData["image"]);
        }

        if ($device->update($formData)) {
            return response()->json($device, 201);
        } else {
            return response("ERROR", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        if ($device->delete()) {
            return response('OK', 200);
        } else {
            return response("ERROR", 500);
        }
    }

    /**
     * Display a table for the current deductions to Device's issues
     *
     * @param \App\Models\Device $device
     * @return \Illuminate\Http\Response
     */
    public function issuesTable(Device $device)
    {
        $current = $device->issues;
        $issues = [];
        foreach (Issue::all() as $issue) {
            $row = [
                "issue" => $issue,
                "deduction" => null,
            ];
            if ($current->contains($issue)) {
                $deduction = $current->firstWhere('id', $issue->id);
                $row['deduction'] = $deduction->pivot->deduction;
            }
            $issues[] = $row;
        }

        return Inertia::render('Devices/Issues', [
            'device' => $device,
            'issueList' => $issues
        ]);
    }

    /**
     * Updates issues list and deduction for a Device
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Device $device
     *
     * @return \Illuminate\Http\Response
     */
    public function issues(Request $request, Device $device)
    {
        $issues = collect($request->input("issues"));

        $issues = $issues->mapWithKeys(function ($issue) {
            return [
                $issue["issue"]["id"] => [
                    "deduction" => $issue["deduction"]
                ]
            ];
        });

        $device->issues()->sync($issues);

        return response()->json("OK", 200);
    }

    /** Updates custom price for a Device
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Device $device
     *
     * @return \Illuminate\Http\Response */
    public function customPrice(Request $request, Device $device)
    {
        if (!Auth::user()->store_id > 0) {
            abort(400);
        }

        $customPrice = $request->input("custom_price");
        $deviceCustomPrice = DeviceStorePrice::updateOrCreate([
            "store_id" => Auth::user()->store_id,
            "device_id" => $device->id,
        ], [
            "custom_price" => $customPrice,
        ]);

        return response()->json("OK", 200);
    }
}
