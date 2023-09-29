<?php 
namespace Pop\Routing;

class Support 
{
    const SEPARATOR = "/";
    const PARAMETER_PATTERN = "/^{(.+)}$/";

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        return !empty($uri)? $uri : "/";
    }
}
