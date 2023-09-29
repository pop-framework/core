<?php
namespace Pop;

use Pop\Routing\Matcher;
use Pop\Cache\CacheFactory;
use Pop\Cli\CliFactory;
use Pop\Error\ErrorFactory;
use Pop\Routing\RouterFactory;
use Pop\Routing\RoutesFactory;
use Pop\Database\DatabaseFactory;
use Pop\Security\SecurityFactory;
use Pop\Template\TemplateFactory;
use Pop\Framework\FrameworkFactory;
use Pop\Parameters\ParametersFactory;
use Pop\Error\Support as ErrorSupport;
use Pop\Environment\EnvironmentFactory;
use Pop\Http\Request;
use Pop\Http\Response;

class Launcher
{
    public FrameworkFactory $framework;
    public CliFactory $cli;
    public CacheFactory $cache;
    public DatabaseFactory $database;
    public EnvironmentFactory $environment;
    public ErrorFactory $error;
    public RouterFactory $router;
    public RoutesFactory $routes;
    public SecurityFactory $security;
    public TemplateFactory $template;
    public ParametersFactory $parameters;
    public Request $request;

    public string $support;

    public function __construct(
        public string $directory,
        public ?array $args = null,
    ){
       
        $this->support = $args === null ? "web" : "cli";

        if ($this->support === 'cli')
        {
            $this->cli = new CliFactory($this, $args);
        }

        $this->framework = new FrameworkFactory($this);
        $this->cache = new CacheFactory($this);
        $this->database = new DatabaseFactory($this);
        $this->environment = new EnvironmentFactory($this);
        $this->error = new ErrorFactory($this);
        $this->router = new RouterFactory($this);
        $this->parameters = new ParametersFactory($this);

        if ($this->support === 'web')
        {
            $this->routes = new RoutesFactory($this);
            $this->security = new SecurityFactory($this);
            $this->template = new TemplateFactory($this);
            $this->request = new Request($this);
        }

    }

    public function start()
    {
        if ($this->support === 'web')
        {
            session_start();
        
            // Init the PHP Error Reporting
            (new ErrorSupport($this->error))->reporting();
    
            // Find the current route
            (new Matcher($this->request, $this->routes, $this->router))->fromRequest();
    
            // Compiler execution
            (new Response($this))->render();
        }
        if ($this->support === 'cli')
        {
            $this->cli->execute();
        }
    }
}