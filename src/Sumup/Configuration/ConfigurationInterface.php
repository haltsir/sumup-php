<?php

namespace Sumup\Api\Configuration;

interface ConfigurationInterface
{
    public function getApiEndpoint();
    public function setApiEndpoint(string $apiEndpoint);
    public function getApiVersion();
    public function setApiVersion(string $apiVersion);
    public function getGrantType();
    public function setGrantType(string $grantType);
    public function getFullEndpoint();
}
