<?php 
namespace Pop\Routing;

use Pop\Routing\Support;

class Generator 
{
    public function __construct(
        private RoutesFactory $routes
    ){}

    public function generate(string $name, array $parameters = [], bool $isAbsolute = false): string
    {
        $routes = $this->routes->getAll();
        $host   = $isAbsolute ? $this->host() : '';
        $path   = '';
        
        if (!isset($routes[$name]))
        {
            throw new \Exception("The route $name is not defined.");
        }

        foreach ($routes as $route) 
        {
            if ($route['name'] == $name) 
            {
                $path = $route['path'];
                break;
            }
        }

        $sections = explode(Support::SEPARATOR, $path);
        unset($sections[0]);
    
        $path = [];
        foreach ($sections as $term)
        {
            $isParam = preg_match(Support::PARAMETER_PATTERN, $term, $param);
            $key = $param[1] ?? null;
            $value = $isParam ? $parameters[$key] : $term;

            array_push($path, $value);
        }
        $path = Support::SEPARATOR.implode(Support::SEPARATOR, $path);

        return $host.$path;
    }

    private function host(): string 
    {
        // Le schema : http ou https
        $scheme = "http";
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        }
        
        // Le domaine : site.com
        $host = "127.0.0.1";
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        }
    
        // Création de la base de l'adresse absolue
        return $scheme."://".$host;
    }
}