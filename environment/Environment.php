<?php 
namespace Pop\Environment;

abstract class Environment {
    const RULES = [
        'environment' => [
            'type'    => 'string',
            'require' => false,
            'default' => "auto",
        ],
        'dev_domains' => [
            'type'    => 'array',
            'require' => false,
            'default' => [
                "::1",
                "127.0.0.1",
                "localhost",
            ],
        ],
    ];
}