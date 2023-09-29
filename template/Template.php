<?php 
namespace Pop\Template;

abstract class Template {
    const RULES = [
        'directory' => [
            'type'    => 'string',
            'require' => false,
            'default' => "./templates",
        ],
        'engine' => [
            'type'    => 'string',
            'require' => false,
            'default' => "php",
        ],
    ];
}