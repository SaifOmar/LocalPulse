<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Actions\Pulses\CreatePulseAction;
use App\Http\Requests\StorePulseRequest;
use App\Http\Requests\UpdatePulseRequest;
use App\Models\Pulse;

use function response;

class PulseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePulseRequest $request, CreatePulseAction $action)
    {
        $account = Account::find($request->account_id);
        $data = $action->store($account, $request->all());
        return response()->json([
            'data' => $data,
        ])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pulse $pulse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pulse $pulse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePulseRequest $request, Pulse $pulse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pulse $pulse)
    {
        //
    }
}
