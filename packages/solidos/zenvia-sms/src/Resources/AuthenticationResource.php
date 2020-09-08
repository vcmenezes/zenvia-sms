<?php

namespace Solidos\ZenviaSms\Resources;

use Solidos\ZenviaSms\Exceptions\AuthenticationNotFoundedException;

class AuthenticationResource
{
    private string $account;
    private string $password;

    /**
     * AuthenticationResource constructor.
     * @param string $account
     * @param string $password
     * @throws AuthenticationNotFoundedException
     */
    public function __construct(string $account, string $password)
    {
        if (blank($account) || blank($password)) {
            throw new AuthenticationNotFoundedException();
        }
        $this->account = $account;
        $this->password = $password;
    }

    public function getKey(): string
    {
        return base64_encode($this->account . ':' . $this->password);
    }
}
