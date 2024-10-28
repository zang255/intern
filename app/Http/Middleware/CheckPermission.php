<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();
        // dd($user);
        if (!$user || !$user->role->permissions()->where('name', $permission)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập.'
            ], 403);
        }
        return $next($request);
    }
}