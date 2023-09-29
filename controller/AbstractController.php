<?php 
namespace Pop\Controller;

use Pop\Compiler\Compiler;
use Pop\Http\Request;
use Pop\Launcher;
use Pop\Routing\RouterFactory;
use Pop\Parameters\ParametersFactory;
use Pop\Routing\Generator as UrlGenerator;

abstract class AbstractController 
{
    private Compiler $compiler;

    protected $databaseManager;

    public ParametersFactory $parameters;
    public Request $request;
    public RouterFactory $router;
    public UrlGenerator $url;

    public function __construct(private Launcher $launcher)
    {
        $this->compiler = new Compiler($this->launcher);
        $this->request = $this->launcher->request;
        $this->url = new UrlGenerator($this->launcher->routes);
        
        // Get the model associate to the caller controller
        $model = defined('static::MODEL')
            ? static::MODEL
            : str_replace("Controller", 'Model', get_called_class());
        ;
        if (class_exists($model))
        {
            $this->databaseManager = new $model($this->launcher->database);
        }
    }

    public function render(string $template, array $data=[])
    {
        return $this->compiler->document($template, $data);
    }

    public function json(array $data=[])
    {
        return $this->compiler->document($data);
    }

    protected function form($class, $entity=null)
    {
        return new $class($entity);
    }

    protected function redirect(string $url): void
    {
        header("location: $url");
        exit;
    }
    protected function redirectTo(string $route, array $params=[]): void
    {
        $url = $this->url->generate($route, $params);
        $this->redirect($url);
    }
}
