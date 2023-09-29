<?php 
namespace Pop\Cache;

abstract class CacheSettings {
    const RULES = [
        'enabled' => [
            'type'    => 'boolean',
            'require' => false,
            'default' => true,
        ],
        'expire' => [
            'type'    => 'integer',
            'require' => false,
            'default' => 3600,
        ],
        'directory' => [
            'type'    => 'string',
            'require' => false,
            'default' => "./cache",
        ],
    ];
}
