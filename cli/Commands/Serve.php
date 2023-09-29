<?php 
namespace Pop\Cli\Commands;

use Pop\Cli\AbstractCommands;

class Serve extends AbstractCommands
{
    public static function getName(): string
    {
        return "serve";
    }

    public function execute(): void
    {
        shell_exec("php -S localhost:1234 -t public/");
    }
}