<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter
{
    public function increment(string $key, array $tags = null) : int
    {
        // Cache for Counter
        $sessionId = session()->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";

        // Get data $userKey di cache dan dengan defaultvalue adalah [] empty
        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if (
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {

            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);

        // Mengecek counter apakah ada di cache
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $diffrence);
        }

        // Mengambil data counter dari cache
        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return $counter;
    }
}
