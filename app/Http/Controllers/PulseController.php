<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Actions\Pulses\CreatePulseAction;
use App\Actions\Pulses\UpdatePulseAction;
use App\Http\Requests\StorePulseRequest;
use App\Http\Requests\UpdatePulseRequest;
use App\Http\Resources\PulseResource;
use App\Models\Pulse;

use function response;

class PulseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pulses = Pulse::latest()->get(); // Or use paginate() if needed
        dump($data = PulseResource::collection($pulses));
        return response()->json(
            $data
        )->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePulseRequest $request, CreatePulseAction $action)
    {
        $data = $action->store($request->user()->getActiveAccount(), $request->all());
        return response()->json(
            new PulseResource($data)
        )->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pulse $pulse)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePulseRequest $request, Pulse $pulse, UpdatePulseAction $action)
    {
        $pulse = $action->update($pulse, $request->validated());
        return response()->json(
            new PulseResource($pulse)
        )->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pulse $pulse)
    {
        $pulse->delete();
        return response()->json()->setStatusCode(200);
    }
}
