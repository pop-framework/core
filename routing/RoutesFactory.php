<?php 
namespace Pop\Routing;

use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class RoutesFactory extends ConfigurationFactory implements ConfigurationInterface
{
    private array $routes = [];

    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_routes'),
        );

        // Import the custom routes
        $routes = $this->import($file);


        // Add the 404 route
        // $this->launcher->routes->add([
        // $this->add([
        //     'controller'  => "Pop\Error\ErrorController::_404",
        //     'methods'     => ['HEAD', 'GET'],
        //     'name'        => "error:404",
        //     'path'        => "/404",
        // ]);

        // Validate & Add custom routes
        array_walk($routes, function($route) {$this->add($route);});

        return $this;
    }



    public function add(array $route): self
    {
        $name              = $this->name($route);
        $controller        = $this->controller($route);
        $controller_class  = $controller['class'];
        $controller_method = $controller['method'];
        $path              = $this->path($route);
        $pattern           = $this->toRegex($route);
        $methods           = $this->methods($route);
        $requirement       = $this->requirement($route);
        $defaults          = $this->defaults($route);

        $this->routes[$name] = [
            'name'              => $name,
            'controller_class'  => $controller_class,
            'controller_method' => $controller_method,
            'path'              => $path,
            'pattern'           => $pattern,
            'methods'           => $methods,
            'requirement'       => $requirement,
            'defaults'          => $defaults,
        ];

        return $this;
    }
    public function getAll(): array 
    {
        return $this->routes;
    }

    /**
     * Validate and return a route's Name
     *
     * @param array $route
     * @return string
     */
    private function name(array $route): string
    {
        // Throw Exception if the route name is not defined
        if (!isset($route['name']))
        {
            throw new \Exception("Route name expected");
        }

        return $route['name'];
    }

    /**
     * Validate and return a route's controller class and method
     *
     * @param array $route
     * @return string
     */
    private function controller(array $route): array
    {
        // Throw Exception if the route controller is not defined
        if (!isset($route['controller']))
        {
            throw new \Exception("Route controller expected");
        }

        $controller          = $route['controller'];
        $controller_sections = explode('::', $controller);
        $controller_class    = $controller_sections[0];
        $controller_method   = $controller_sections[1] ?? 'index';

        // Throw Exception if class is not defined
        if (!class_exists($controller_class))
        {
            throw new \Exception("The controller class \"$controller_class\" was not found in your project.");
        }

        // Throw Exception if method is not defined
        if (!method_exists($controller_class, $controller_method))
        {
            throw new \Exception("The controller method \"$controller_method\" was not found in the class \"$controller_class\".");
        }

        return [
            'class'  => $controller_class,
            'method' => $controller_method,
        ];
    }

    /**
     * Validate and return a route's Path
     *
     * @param array $route
     * @return void
     */
    private function path(array $route): string
    {
        // Throw Exception if the route path is not defined
        if (!isset($route['path']))
        {
            throw new \Exception("Route path expected");
        }

        return $route['path'];
    }

    /**
     * Validate and return a route's Http Methods allowed
     *
     * @param array $route
     * @return array
     */
    private function methods(array $route): array
    {
        return $route['methods'] ?? ['HEAD', 'GET'];
    }

    /**
     * Validate and return a route's Requirement params
     *
     * @param array $route
     * @return array
     */
    private function requirement(array $route): array
    {
        $requirement = $route['requirement'] ?? [];
        
        return $requirement;
    }

    /**
     * Validate and return a route's Default params value
     *
     * @param array $route
     * @return array
     */
    private function defaults(array $route): array
    {
        $defaults = $route['defaults'] ?? [];
        
        return $defaults;
    }






    private function toRegex(array $route): string
    {
        $sections = explode("/", $route['path']);
        unset($sections[0]);
    
        foreach ($sections as $key => $term)
        {
            $isParam = preg_match("/^{(.+)}$/", $term, $param);
            $regex = $isParam && isset($param[1]) && isset($route['requirement'][$param[1]]) ? $route['requirement'][$param[1]] : ".+";
            $param = $param[1] ?? null;

            $sections[$key] = [
                'term'    => $term,
                'isParam' => $isParam,
                'regex'   => $regex,
                'param'   => $param,
            ];
        }

        $re = '';
        foreach ($sections as $key => $section)
        {
            $re.= $section['isParam'] 
                ? '/(?P<'.$section['param'].'>'.$section['regex'].')'
                : '/'.$section['term']
            ;
        }

        return $re;
    }
}