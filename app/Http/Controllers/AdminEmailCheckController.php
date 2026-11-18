<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminEmailCheckController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:254'],
        ]);

        $exists = User::query()
            ->where('email', $validated['email'])
            ->where('is_admin', true)
            ->exists();

        return response()->json(['registered' => $exists])
            ->header('Cache-Control', 'no-store, private');
    }
}
