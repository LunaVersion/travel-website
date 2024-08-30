<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\PasswordService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;
    protected $passwordService;

    public function __construct(AuthService $authService, PasswordService $passwordService)
    {
        $this->authService = $authService;
        $this->passwordService = $passwordService;
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8|max:20' //|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/
            ]);

            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Wrong email or password'
                ]);
            }

            $request->session()->regenerate();

            return response()->json([
                'status_code' => 200,
                'message' => 'Login successful'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred during login: ' . $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'status_code' => 200,
                'message' => 'Logout successful'
            ]);
        } catch (Exception $e) {
            \Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred during logout: ' . $e->getMessage()
            ]);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $token = $this->passwordService->handleForgotPassword($request->email);

            if (!$token) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'User not found'
                ]);
            }

            $resetLink = url('/reset-password?token=' . $token);

            return response()->json([
                'status_code' => 200,
                'message' => 'Password reset email sent',
                'reset_link' => $resetLink
            ]);
        } catch (Exception $e) {
            \Log::error('Forgot Password error: ' . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred during forgot password process: ' . $e->getMessage()
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'password' => 'required|confirmed|min:8|max:20'
            ]);

            $user = $this->passwordService->resetPassword($request->token, $request->password);

            if (!$user) {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Invalid or expired token'
                ]);
            }

            $this->authService->updateUserPassword($user, $request->password);
            $this->passwordService->deleteToken($request->token);

            return response()->json([
                'status_code' => 200,
                'message' => 'Password has been reset'
            ]);
        } catch (Exception $e) {
            \Log::error('Reset Password error: ' . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred during password reset: ' . $e->getMessage()
            ]);
        }
    }
}
