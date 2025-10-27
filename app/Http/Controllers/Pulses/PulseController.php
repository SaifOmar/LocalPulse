<?php

namespace App\Http\Controllers\Pulses;

use App\Http\Controllers\Controller;
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
        return response()->json(
            PulseResource::collection($pulses)
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
     * Update the specified resource in storage.
     */
    public function update(Pulse $pulse, UpdatePulseRequest $request, UpdatePulseAction $action)
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
        try {
            $pulse->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete'], 500);
        }
    }
}
