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

    private function message(string $message, int $colorCode)
    {
        echo "\033[{$colorCode}m> {$message}\033[0m";
        // echo "\n";
        echo "\n";
    }
    protected function info(string $message): void
    {
        $this->message($message, 34);
    }
    protected function success(string $message): void
    {
        $this->message($message, 32);
    }
    protected function error(string $message): void
    {
        $this->message($message, 31);
    }
    protected function warning(string $message): void
    {
        $this->message($message, 33);
    }


    protected function isSchemaExist($stmt, $schema): bool
    {
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :schema";

        $query = $stmt->prepare( $sql );
        $query->bindParam(':schema', $schema, \PDO::PARAM_STR);
        $query->execute();

        return !!$query->fetch(\PDO::FETCH_ASSOC);
    }
}