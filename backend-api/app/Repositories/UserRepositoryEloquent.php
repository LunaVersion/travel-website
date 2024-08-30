<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function model()
    {
        return User::class;
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }
        return null;
    }

    public function logout()
    {
        Auth::logout();
    }

    public function forgotPassword(string $email)
    {
        $user = User::findByEmail($email);
        if (!$user) {
            $user->updateOrCreateToken();
        }

        if ($user) {
            Mail::to($user->email)->send(new PasswordResetMail($user));
            return response()->json(['message' => 'Password reset email sent.']);
        }

        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        return $token;
    }

    public function resetPassword(string $token, string $password)
    {
        $resetRecord = DB::table('password_resets')->where('token', $token)->first();
        if (!$resetRecord) {
            return null;
        }

        $user = User::where('email', $resetRecord->email)->first();
        if (!$user) {
            return null;
        }

        $user->password = Hash::make($password);
        $user->save();

        DB::table('password_resets')->where('token', $token)->delete();

        return $user;
    }
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
