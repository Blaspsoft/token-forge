<?php

namespace Blaspsoft\TokenForge\Controllers\Blade;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        return view('api.index', [
            'tokens' => $request->user()->tokens,
            'availablePermissions' => config('token-forge.available_permissions'),
            'defaultPermissions' => config('token-forge.default_permissions'),
        ]);
    }

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

        return back();
    }

    public function update(Request $request, $tokenId)
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => $request->permissions,
        ])->save();

        return back();
    }

    public function destroy(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return back();
    }
}
