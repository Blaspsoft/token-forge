<?php

namespace Blaspsoft\TokenForge\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;

class FlashInertiaData extends Middleware
{
    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'flash' => [
                'breezeToken' => [
                    'token' => fn () => session()->get('token'),
                ],
            ]
        ];
    }
}
