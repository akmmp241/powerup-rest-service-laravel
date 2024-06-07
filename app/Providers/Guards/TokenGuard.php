<?php

namespace App\Providers\Guards;

use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isEmpty;

class TokenGuard implements Guard
{
    use GuardHelpers;

    private Request $request;

    public function __construct(UserProvider $userProvider, Request $request)
    {
        $this->provider = $userProvider;
        $this->request = $request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }


    public function user(): Authenticatable|User|null
    {
        if ($this->user != null) {
            return $this->user;
        }

        $token = $this->request->header("POWERUP-API-KEY");
        if ($token) {
            $this->user = $this->provider->retrieveByCredentials(["personal_token" => $token]);
        }

        return $this->user ?? null;
    }

    public function id()
    {
        if ($this->user != null) {
            return $this->user->uuid;
        }

        $token = $this->request->header("POWERUP-API-KEY");
        if ($token) {
            $this->user = $this->provider->retrieveByCredentials(["personal_token" => $token]);
        }

        return $this->user->uuid ?? null;
    }

    public function validate(array $credentials = []): bool
    {
        return $this->provider->validateCredentials($this->user, $credentials);
    }

    public function attempt(array $credentials = []): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if (is_null($user)) {
            return false;
        }

        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    public function check(): bool
    {
        if ($this->user != null) {
            return true;
        }

        $token = $this->request->header("POWERUP-API-KEY");
        if (!$token) return false;

        $user = $this->provider->retrieveByCredentials(["personal_token" => $token]);
        return $user !== null;
    }
}
