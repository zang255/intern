<?php 
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập.'
            ], 403);
        }
        return $next($request);
    }
}