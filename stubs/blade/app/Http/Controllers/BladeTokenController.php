<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Blaspsoft\TokenForge\Contracts\TokenForgeController;

class BladeTokenController extends Controller implements TokenForgeController
{
    /**
     * Show the user API token management screen.
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index(Request $request)
    {
        return view('api.index', [
            'tokens' => $request->user()->tokens,
            'availablePermissions' => config('token-forge.available_permissions'),
            'defaultPermissions' => config('token-forge.default_permissions'),
        ]);
    }

    /**
     * Store a new API token for the user.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'in:'.implode(',', config('token-forge.available_permissions')),
        ]);

        $token = $request->user()->createToken($request->name, $request->permissions);

        $request->session()->flash('status', 'token-created');
        $request->session()->flash('token-forge', $token->plainTextToken);

        return redirect()->route('api-tokens.index');
    }

    /**
     * Update the given API token's permissions.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $tokenId
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function update(Request $request, $tokenId)
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => $request->permissions,
        ])->save();

        return redirect()->route('api-tokens.index');
    }

    /**
     * Delete the given API token.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $tokenId
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function destroy(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('api-tokens.index');
    }
}
