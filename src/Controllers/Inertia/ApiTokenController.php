<?php

namespace Blaspsoft\TokenForge\Controllers\Inertia;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token management screen.
     * 
     * @return \Inertia\Response
     * 
     */
    public function index()
    {
        return Inertia::render('ApiTokens/Index', [
            'tokens' => auth()->user()->tokens,
            'availablePermissions' => [
                'create',
                'read',
                'update',
                'delete',
            ],
            'defaultPermissions' => [
                'read',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $token = $request->user()->createToken($request->name, $request->permissions);

        $request->session()->flash('token', $token->plainTextToken);

        return redirect()->route('api-tokens.index');
    }

    public function update(Request $request, $tokenId)
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => $request->permissions,
        ])->save();

        return redirect()->route('api-tokens.index');
    }

    public function destroy(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('api-tokens.index');
    }
}
