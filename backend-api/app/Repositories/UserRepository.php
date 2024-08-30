<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface UserRepository extends RepositoryInterface
{
    public function login(array $credentials);

    public function logout();

    public function forgotPassword(string $email);

    public function findByEmail($email);
    
    public function resetPassword(string $token, string $password);
}
