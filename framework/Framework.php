<?php 
namespace Pop\Framework;

abstract class Framework {
    const RULES = [
        'configuration_file_cache' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/cache.php",
        ],
        'configuration_file_database' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/database.php",
        ],
        'configuration_folder_database' => [
            'type'    => 'string',
            'require' => false,
            'default' => "database",
        ],
        'configuration_file_environment' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/environment.php",
        ],
        'configuration_file_framework' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/framework.php",
        ],
        'configuration_file_parameters' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/parameters.php",
        ],
        'configuration_file_router' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/router.php",
        ],
        'configuration_file_routes' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/routes.php",
        ],
        'configuration_file_security' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/security.php",
        ],
        'configuration_file_template' => [
            'type'    => 'string',
            'require' => false,
            'default' => "config/template.php",
        ]
    ];
}