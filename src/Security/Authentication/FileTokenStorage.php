<?php

namespace Sumup\Api\Security\Authentication;

class FileTokenStorage extends TokenStorage
{
    const DEFAULT_STORAGE_FILENAME = 'sumup_token_storage';

    /**
     * @var string
     */
    protected $storagePath;

    /**
     * @var string
     */
    protected $storageFilename;

    /**
     * FileTokenStorage constructor.
     * @param string $storagePath
     * @param null $storageFilename
     */
    public function __construct($storagePath, $storageFilename = null)
    {
        if (0 !== strrpos($storagePath, DIRECTORY_SEPARATOR)) {
            $storagePath .= DIRECTORY_SEPARATOR;
        }

        $this->storagePath = $storagePath;
        $this->storageFilename = $storageFilename ?: self::DEFAULT_STORAGE_FILENAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(): ?string
    {
        if (empty($this->storagePath) || !file_exists($this->storagePath . $this->storageFilename)) {
            return null;
        }

        $jsonData = file_get_contents($this->storagePath . $this->storageFilename);
        $data = json_decode($jsonData);
        if (false === ($data instanceof \stdClass) || false === property_exists($data, 'token')) {
            return null;
        }

        return $data->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(string $token)
    {
        if (empty($this->storagePath)) {
            throw new \Exception('Unknown token storage path.');
        }

        if (!file_exists($this->storagePath)) {
            $this->createStoragePath();
        }

        if (!is_writable($this->storagePath)) {
            throw new \Exception('Cannot write to token storage path.');
        }

        if (false === is_string($token)) {
            throw new \Exception('Unknown token format.');
        }

        return !!file_put_contents(
            $this->storagePath . $this->storageFilename,
            json_encode(['token' => $token])
        );
    }

    public function resetStorage()
    {
        unlink($this->storagePath . $this->storageFilename);
    }

    private function createStoragePath()
    {
        mkdir($this->storagePath, 0777, true);
    }
}
