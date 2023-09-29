<?php 
namespace Pop\Http;

use Pop\FileSystem\Path;
use Pop\Launcher;
use Pop\Routing\Support;

class Response 
{
    private Path $path;
    private Support $support;

    public function __construct(
        private Launcher $launcher,
    ){
        $this->path    = new Path;
        $this->support = new Support;
    }

    public function render()
    {
        $this->launcher->cache->get('enabled')
            ? $this->cacheRender()
            : $this->immediateRender()
        ;
    }

    private function immediateRender()
    {
        $route  = $this->launcher->router->get('current');
        $class  = $route['controller_class'];
        $method = $route['controller_method'];
        $params = $route['params'] ?? [];

        echo "<!-- Generated at ".date('Y-m-d H:i:s')." -->\n";
        echo (new $class($this->launcher))->$method(...$params);
    }

    private function cacheRender()
    {
        $directory = (string) $this->launcher->framework->get('cache_root');

        is_dir($directory) ?: mkdir($directory);

        $file = $this->path->join(
            $directory,
            md5($this->support->uri())
        );

        $expire = (int) $this->launcher->cache->get('expire');
        $expire = time() - $expire;
        
        if (!file_exists($file) || filemtime($file) < $expire)
        {
            ob_start();
            $this->immediateRender();
            file_put_contents($file, ob_get_contents()) ; 
            ob_end_clean();
        }

        readfile($file);
    }
}