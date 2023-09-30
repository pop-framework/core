<?php 
namespace Pop\Cli\Commands;

use Pop\Cli\AbstractCommands;

class Serve extends AbstractCommands
{
    public static function getName(): string
    {
        return "serve";
    }

    public function execute(array $options=[]): void
    {
        $port = $options['port'] ?? $options['p'] ?? 8080;
        $target = $options['target'] ?? $options['t'] ?? 'public/';

        shell_exec("php -S localhost:$port -t $target");
    }
}