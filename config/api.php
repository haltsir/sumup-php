<?php

return [
    'endpoint' => 'https://api.sumup.com',
    'version' => 'v0.1',
    'token_storage_type' => 'file',
    'token_storage' => \Sumup\Api\Security\Authentication\FileTokenStorage::class,
    'token_storage_path' => sys_get_temp_dir()
];
