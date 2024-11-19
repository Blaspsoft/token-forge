<?php

namespace Blaspsoft\TokenForge\Contracts;

use Illuminate\Http\Request;

interface TokenForgeController
{
    public function index(Request $request);

    public function store(Request $request);

    public function update(Request $request, $tokenId);

    public function destroy(Request $request, $tokenId);
}
