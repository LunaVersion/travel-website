<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Repositories\UserRepository;
use App\Repositories\ForgotPasswordRepository;
use App\Mail\PasswordResetMail;

class PasswordService
{
    protected $userRepository;
    protected $forgotPasswordRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handleForgotPassword($email)
    {
        $user = $this->userRepository->findByEmail($email);

        // if (!$user) {
        //     return null;
        // }

        // $token = Str::random(64);
        // $this->forgotPasswordRepository->updateOrCreateToken(
        //     $email,
        //     $token,
        //     now()->addMinutes(10)
        // );

        // Mail::to($user->email)->send(new PasswordResetMail($token));
        // return $token;
        // $user = User::findByEmail($email); 

        if ($user) {
            $token = $this->updateOrCreateToken($user); // Gọi phương thức với đối tượng hợp lệ
            return $token;
        } else {
            throw new \Exception('User not found');
        }
    }

    public function validateToken($token)
    {
        return $this->forgotPasswordRepository->findByToken($token);
    }

    public function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
    }

    public function deleteToken($token)
    {
        $this->forgotPasswordRepository->deleteByToken($token);
    }

    public function updateOrCreateToken($user)
    {
        // Ví dụ cách bạn có thể định nghĩa phương thức này
        // Thực hiện hành động với $user và tạo hoặc cập nhật token
        $token = $user->createToken('YourAppName')->plainTextToken;
        return $token;
    }
}

