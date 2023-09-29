<?php 
namespace Pop\Cli;

use Pop\Launcher;
use Pop\FileSystem\Path;

class AbstractCommands 
{
    protected Path $path;

    public function __construct(
        protected Launcher $launcher,
    ){
        $this->path = new Path;
    }

    protected function info(string $message): void
    {
        echo "\n";
        echo $message;
        echo "\n\n";
    }
}