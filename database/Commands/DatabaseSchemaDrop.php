<?php 
namespace Pop\Database\Commands;

use Pop\Cli\AbstractCommands;
use Pop\Database\DatabaseConnection;

class DatabaseSchemaDrop extends AbstractCommands
{
    public static function getName(): string
    {
        return "db:schema:drop";
    }

    public function execute(array $options=[]): void
    {
        // Retrieve Databases config
        $config = $this->launcher->database->getAll();

        // Create STMT
        $connections = new DatabaseConnection($this->launcher->database, false);

        if (isset($options['force']))
        {
            foreach ($config as $name => $info)
            {
                $stmt = $connections->getStmt($name);
    
                if ($this->isSchemaExist($stmt, $info['schema']))
                {
                    $sql = "DROP DATABASE ".$info['schema'].";";
                    $query = $stmt->prepare( $sql );
                    $query->execute();
    
                    $this->isSchemaExist($stmt, $info['schema'])
                        ? $this->error(sprintf("The schema %s was not removed", $info['schema']))
                        : $this->success(sprintf("The schema %s was successfully remove", $info['schema']))
                    ;
                }
            }
        }
        else 
        {
            $this->info("Use the option --force");
            $this->info("");
            $this->info("\tpop db:drop --force");
            $this->info("");
        }
    }
}