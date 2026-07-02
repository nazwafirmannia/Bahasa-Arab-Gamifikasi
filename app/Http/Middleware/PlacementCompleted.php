<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlacementCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Admin skip placement
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        // User wajib placement
        if (!$user->has_taken_placement) {
            return redirect()->route('placement.show');
        }
        
        return $next($request);
    }
}