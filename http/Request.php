<?php 
namespace Pop\Http;

use Pop\Launcher;

class Request 
{
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_PATCH  = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    private string $method;

    public function __construct(Launcher $launcher)
    {
        // Remix the HTTP Method
        $this->method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    }

    public function getMethod(): string 
    {
        return $this->method;
    }
}