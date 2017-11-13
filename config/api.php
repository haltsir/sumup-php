<?php

return [
    'endpoint' => 'https://api.sumup.com',
    'version' => 'v0.1',
    'cache_pool' => \Sumup\Api\Cache\File\FileCacheItemPool::class,
    'file_cache_path' => sys_get_temp_dir()
];
