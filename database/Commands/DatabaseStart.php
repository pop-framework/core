<?php 
namespace Pop\Database\Commands;

use Pop\Cli\AbstractCommands;

class DatabaseStart extends AbstractCommands
{
    public static function getName(): string
    {
        return "db:start";
    }

    public function execute(array $options=[]): void
    {
        // echo "DB STARTER";

        shell_exec("/usr/local/Cellar/mysql/8.0.33_3/support-files/mysql.server stop >> /dev/null");
        shell_exec("/usr/local/Cellar/mysql/8.0.33_3/support-files/mysql.server start >> /dev/null");
    }
}