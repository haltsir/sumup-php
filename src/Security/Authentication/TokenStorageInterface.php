<?php

namespace Sumup\Api\Security\Authentication;

interface TokenStorageInterface
{
    public function getToken(): ?string;
    public function setToken(string $token);
}
