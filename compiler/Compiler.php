<?php 
namespace Pop\Compiler;

// Hello There !!!

use Pop\Launcher;
use Pop\Compiler\Support;
use Pop\Components\ComponentFactory;
use Pop\FileSystem\Path;
use Pop\Http\Request;
// use Pop\Compiler\Compiler;
// use Pop\Launcher;
use Pop\Routing\RouterFactory;
use Pop\Parameters\ParametersFactory;
use Pop\Routing\Generator as UrlGenerator;

class Compiler 
{
    private Path $path;
    private string $engine;

    public ParametersFactory $parameters;
    public ComponentFactory $components;
    public Request $request;
    public RouterFactory $router;
    public UrlGenerator $url;

    public function __construct(
        private Launcher $launcher,
    ){
        $this->engine = $this->launcher->template->get('engine');
        $this->path = new Path;

        $this->parameters = $this->launcher->parameters;
        $this->components = $this->launcher->components;
        $this->request    = $this->launcher->request;
        $this->router     = $this->launcher->router;
        $this->url        = new UrlGenerator($this->launcher->routes);
    }

    public function document(string $template, array $data=[])
    {
        header("content-type: text/html");

        switch($this->engine)
        {
            case Support::ENGINE_PHP:
                return $this->php_compilation($template, $data);
            break;
        }
    }

    public function json(array $data=[])
    {
        header("content-type: application/json");

        ob_start(); 
        echo json_encode($data);
        return ob_get_clean(); 
    }

    private function php_compilation(string $template, array $data=[]): string
    {
        $project_root = $this->launcher->framework->get('project_root');
        $template_dir = $this->launcher->template->get('directory');
        $file_extension = $this->launcher->template->get('engine');
        foreach ($data as $key => $value) $$key = $value;

        // Document content
        ob_start();
        include $this->path->join( $project_root, $template_dir, "$template.$file_extension" );
        $document = ob_get_clean();

        // Document base
        if (defined('BASE'))
        {
            ob_start(); 
            define('pop_content', $document); 
            include $this->path->join( $project_root, $template_dir, 'bases', BASE.".$file_extension" );
            $document = ob_get_clean();
        }
            
        return $document;
    }
}