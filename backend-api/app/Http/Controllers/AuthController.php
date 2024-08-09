<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ForgotPassword;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        
            $credentials = $request->only('email', 'password');
        
            if (Auth::attempt($credentials)) {
                return response()->json(['message' => 'Đăng nhập thành công']);
            }
        
            return response()->json(['error' => 'Thông tin đăng nhập không chính xác'], 401);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return response()->json(['message' => 'Đăng xuất thành công']);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                return response()->json(['error' => 'Không tìm thấy người dùng'], 404);
            }
    
            $token = Str::random(64);
    
            ForgotPassword::updateOrCreate(
                ['email' => $request->email],
                ['token' => $token, 'expired_at' => now()->addMinutes(10)]
            );
    
            $resetLink = url('/reset-password?token=' . $token);
            // Gửi email với token sử dụng Laravel's Mail facade
            Mail::to($user->email)->send(new PasswordResetMail($token), function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Reset Password')
                        ->setBody('<!DOCTYPE html>
                                    <html>
                                    <head>
                                        <title>Reset Password</title>
                                    </head>
                                    <body>
                                        <p>Nhấp vào liên kết để đặt lại mật khẩu: </p>
                                        <p><a href="' . $resetLink . '"></a></p>
                                        <p>Liên kết sẽ hết hạn sau 10 phút.</p>
                                    </body>
                                    </html>', 'text/html');
            });
    
            return response()->json(['message' => 'Đã gửi email đặt lại mật khẩu']);

        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'password' => 'required|confirmed',
            ]);
    
            $forgotPassword = ForgotPassword::where('token', $request->token)
                ->where('expired_at', '>', now())
                ->first();
    
            if (!$forgotPassword) {
                return response()->json(['error' => 'Token không hợp lệ hoặc đã hết hạn'], 400);
            }
    
            $user = User::where('email', $forgotPassword->email)->first();
            if (!$user) {
                return response()->json(['error' => 'Không tìm thấy người dùng'], 404);
            }
    
            $user->password = Hash::make($request->password);
            $user->save();
    
            // Xóa token
            $forgotPassword->delete();
    
            return response()->json(['message' => 'Mật khẩu đã được đặt lại']);

        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }
        
    }
}

