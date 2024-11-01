<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\TransientToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thông tin đăng nhập không chính xác',
            ]);
        }

        $user = User::getByUsername($request->email);
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mật khẩu không hợp lệ',
            ]);
        }
        $existingToken = $user->tokens->first();
        $check_token = User::getByUsername($request->email);
        if ($existingToken && $check_token->remember_token != null) {
            $get_user = User::getByUsername($request->email);
            $tokenResult = $get_user->remember_token;
        } else {
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $save_token = User::updateToken($request->email, $tokenResult);
            if (!$save_token) {
                throw new \Exception('Lỗi truy vấn cơ sở dữ liệu');
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'access_token' => $tokenResult,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                if (Auth::guard('sanctum')->check()) {
                    $accessToken = $user->currentAccessToken();

                    if (!($accessToken instanceof TransientToken)) {
                        $accessToken->delete();
                    }
                } else {
                    Auth::logout();
                }

                $user->remember_token = null;
                $user->save();
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return response()->json([
                'message' => 'Đã đăng xuất thành công',
                'redirect' => route('auth.login')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi đăng xuất', 'message' => $e->getMessage()], 500);
        }
    }
}
