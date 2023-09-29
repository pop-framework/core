<?php 
namespace Pop\Routing;

use Pop\Http\Request;
use Pop\Routing\Support;
use Pop\Routing\RouterFactory;
use Pop\Routing\RoutesFactory;

class Matcher 
{
    private Support $support;

    public function __construct(
        private Request $request,
        private RoutesFactory $routes,
        private RouterFactory $router,
    ){
        $this->support = new Support;
    }

    public function fromRequest()
    {
        foreach ($this->routes->getAll() as $route) 
        {
            if ($route['path'] !== null)
            {
                $re = "@^".$route['pattern']."$@";
                if (preg_match($re, $this->support->uri(), $params)) 
                {
                    $params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);
                    $path = $route['path'];

                    array_walk($params, function(&$param) {
                        $param = explode("/", $param);
                        $param = $param[0];
                    });

                    foreach ($params as $key => $value)
                    {
                        $path = preg_replace("/\{$key\}/", $value, $path);
                    }

                    if ($path === $this->support->uri())
                    {
                        if (!in_array($this->request->getMethod(), $route['methods']))
                        {
                            throw new \Exception(printf("Method not allowed, %s expected, but %s given",
                                implode(", ", $route['methods']),
                                $this->request->getMethod()
                            ));
                        }
                        
                        break;
                    }
                }
            }
        }
        
        $route['params'] = $params ?? [];
        $route['uri'] = $this->support->uri();

        $this->router->set('current', $route);
    }
}
