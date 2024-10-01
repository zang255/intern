<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Sử dụng middleware auth, ngoại trừ hàm login
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    /**
     * Lấy JWT token dựa trên thông tin đăng nhập.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // Lấy thông tin đăng nhập (email và password)
        $credentials = request(['email', 'password']);

        // Nếu thông tin không hợp lệ, trả về lỗi Unauthorized
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Thông tin sai'], 401);
        }

        $refreshToken = $this->create_refresh_token();
        // Trả về token nếu đăng nhập thành công
        return $this->respondWithToken($token, $refreshToken);
    }


    /**
     * Lấy thông tin người dùng đã xác thực.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Đăng xuất người dùng (hủy token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Làm mới token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $refreshToken = request()->refresh_token;
        try {
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            $user = User::find($decoded['user_id']);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            if ($user->refresh_token !== $refreshToken) {
                return response()->json(['error' => 'refresh_token invalid'], 403);
            }
            // xóa token cũ vào blacklist
            auth()->invalidate();

            // Xóa refresh token cũ và phát hành token mới
            $user->refresh_token = null;
            $user->save();

            // tạo token mới
            $token = auth()->login($user);
            $refreshToken = $this->create_refresh_token();
            return $this->respondWithToken($token, $refreshToken);
        } catch (JWTException $e) {
            \Log::error('JWT Exception: ' . $e->getMessage());
            return response()->json(['error' => 'refresh_token invalid'], 500);
        }
        // return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Cấu trúc trả về của token.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    private function create_refresh_token()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'random' => rand() . time(),
            'expires' => time() + config('jwt.refresh_ttl'),

        ];
        $refreshToken = JWTAuth::getJWTProvider()->encode($data);

        $user = auth()->user();
        $user->refresh_token = $refreshToken;
        $user->save();
        return $refreshToken;
    }
}