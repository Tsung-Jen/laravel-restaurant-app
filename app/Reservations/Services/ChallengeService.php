<?php

namespace App\Reservations\Services;

use Illuminate\Support\Facades\Crypt;

class ChallengeService
{
    public function generate(): array
    {
        $a = random_int(1, 20);
        $b = random_int(1, 20);
        $answer = $a + $b;

        return [
            'question' => "{$a} + {$b}",
            'token' => Crypt::encryptString((string) $answer),
        ];
    }

    public function verify(string $token, int $answer): bool
    {
        try {
            return (int) Crypt::decryptString($token) === $answer;
        } catch (\Exception) {
            return false;
        }
    }
}
