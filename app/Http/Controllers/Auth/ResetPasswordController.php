<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Accounts\UserUpdatePasswordAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function store(): JsonResponse
    {
        return response()->json([
            'message' => 'password reset link sent successfully',
        ])->setStatusCode(200);
    }

    public function reset(Request $request, UserUpdatePasswordAction $action): JsonResponse
    {
        $token = $action->sendPasswordResetLink($request->user()->getActiveAccount());
        $link = url('/auth/accounts/'. $request->user()->getActiveAccount()->id .'/password/update/'. $token);
        return response()->json([
            'link' => $link,
        ])->setStatusCode(200);
    }

    public function updatePassword(Request $request, UserUpdatePasswordAction $action): JsonResponse
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);
        $account = $request->user()->getActivAccount();
        $action->updatePassword($account, $request->route('token'), $request->password);
        return response()->json([
            'message' => 'Password updated successfully',
        ])->setStatusCode(200);
    }
}
