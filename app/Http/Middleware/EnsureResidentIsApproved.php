<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureResidentIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $resident = $request->user()?->resident;

        if (! $resident || ! $resident->verified_at) {
            return redirect()
                ->route('home')
                ->with('error', 'Your account is pending admin approval. You can view your details for now.');
        }

        return $next($request);
    }
}
