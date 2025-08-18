<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    /**
     * Show all tokens for the logged-in restaurant owner.
     */
    public function index()
    {
        $tokens = Auth::user()->tokens; // Tokens from Sanctum

        return view('api.index', compact('tokens'));
    }

    /**
     * Generate a new API key (Sanctum token).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Auth::user()->createToken($request->name);

        return response()->json([
            'api_key' => $token->plainTextToken,
            'message' => 'Token generated successfully',
        ]);
    }

    /**
     * Revoke (delete) an API token.
     */
    public function destroy($id)
    {
        $token = Auth::user()->tokens()->where('id', $id)->first();

        if (! $token) {
            return response()->json(['error' => 'Token not found or unauthorized'], 404);
        }

        $token->delete();

        return response()->json(['message' => 'Token revoked']);
    }
}
