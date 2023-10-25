<?php 
namespace Pop\Database\Commands;

use Pop\Cli\AbstractCommands;
use Pop\Database\DatabaseConnection;

class DatabaseCreate extends AbstractCommands
{
    public static function getName(): string
    {
        return "db:create";
    }

    public function execute(array $options=[]): void
    {
        // Retrieve Databases config
        $config = $this->launcher->database->getAll();

        // Create STMT
        $connections = new DatabaseConnection($this->launcher->database, false);

        // For each defined database, check if database exist and create if don't exist
        foreach ($config as $name => $info)
        {
            $stmt = $connections->getStmt($name);


            if ($this->isSchemaExist($stmt, $info['schema']))
            {
                $this->warning(sprintf("The schema %s already exist. Creation skipped", $info['schema']));
            }
            else
            {
                $sql = "CREATE DATABASE ".$info['schema'].";";
                $query = $stmt->prepare( $sql );
                $query->execute();

                $this->isSchemaExist($stmt, $info['schema'])
                    ? $this->success(sprintf("The schema %s was successfully created", $info['schema']))
                    : $this->error(sprintf("The schema %s was not created", $info['schema']))
                ;
            }
        }
    }
}