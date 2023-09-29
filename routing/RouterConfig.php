<?php 
namespace Pop\Routing;

abstract class RouterConfig {
    const RULES = [
        'base' => [
            'type'    => 'string',
            'require' => false,
            'default' => "/",
        ],
    ];
}