<?php

namespace App\Http\Controllers\Accounts;

use App\Actions\Accounts\CreateUserAccountAction;
use App\Actions\Accounts\UpdateUserAccountAction;
use App\Models\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AccountResource;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAccountRequest $request, CreateUserAccountAction $action)
    {
        try {
            $account = $action->afterFirst(Auth::user(), $request->validated());
            return response()->json(new AccountResource($account))->setStatusCode(201);
        } catch (\Exception) {
            throw ValidationException::withMessages([['error' => 'An error occurred while creating account']]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return response()->json(new AccountResource($account))->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account, UpdateUserAccountAction $action)
    {
        try {
            $account = match ($request->requestType()) {
                'display' => $action->updateUserDisplayData($account, $request->validated()),
                'account' => $action->updateUserAccount($account, $request->validated()),
                default => $action->updateMixed($account, $request->validated()),
            };
            return response()->json(new AccountResource($account))->setStatusCode(200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([['error' => $e->getMessage()]]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json(["message" => "Account deleted sad to see you go, rememeber you can always come back to us within 30 days"], 204);
    }
}
