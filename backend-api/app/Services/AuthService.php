<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUserByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function login($credentials)
    {
        return Auth::attempt($credentials);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function updateUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
    }
}
