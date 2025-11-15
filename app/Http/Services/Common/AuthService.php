<?php

namespace App\Http\Services\Common;

use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Verify user credentials with hashed password.
     *
     * @param string $inputPassword
     * @param string $hashedPassword
     * @return bool
     */
    public function verifyPassword(string $inputPassword, string $hashedPassword): bool
    {
        return Hash::check($inputPassword, $hashedPassword);
    }

    /**
     * Hash a password.
     *
     * @param string $password
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * Generate a random password.
     *
     * @param int $length
     * @return string
     */
    public function generatePassword(int $length = 8): string
    {
        return \Illuminate\Support\Str::random($length);
    }
}
