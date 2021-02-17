<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceForm;
use App\Models\Device;
use Illuminate\Http\Request;
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
            'devices' => Device::all()->map(function (Device $device) {
                    return [
                        'id' => $device->id,
                        'name' => $device->name,
                        'company' => $device->company
                    ];
                }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Devices/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
            "storeUrl" => route("device.update", $device),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
}
