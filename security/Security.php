<?php 
namespace Pop\Security;

abstract class Security {
    const RULES = [
        'table' => [
            'type'    => 'string',
            'require' => false,
            'default' => "users",
        ],
        'property_id' => [
            'type'    => 'string',
            'require' => false,
            'default' => "id",
        ],
        'property_username' => [
            'type'    => 'string',
            'require' => false,
            'default' => "username",
        ],
        'property_email' => [
            'type'    => 'string',
            'require' => false,
            'default' => "email",
        ],
        'property_password' => [
            'type'    => 'string',
            'require' => false,
            'default' => "password",
        ],
        'property_roles' => [
            'type'    => 'string',
            'require' => false,
            'default' => "roles",
        ],
        'route_login' => [
            'type'    => 'string',
            'require' => false,
            'default' => "login",
        ],
        'route_logout' => [
            'type'    => 'string',
            'require' => false,
            'default' => "logout",
        ],
    ];
}