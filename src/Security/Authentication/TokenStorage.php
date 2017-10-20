<?php

namespace Sumup\Api\Security\Authentication;

abstract class TokenStorage implements TokenStorageInterface
{
    /**
     * @var string
     */
    private $token;

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }
}
