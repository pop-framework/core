<?php 
namespace Pop\Database\Commands;

use Pop\Cli\AbstractCommands;
use Pop\Database\DatabaseConnection;

class DatabaseTablesCreate extends AbstractCommands
{
    private array $tables = [];

    public static function getName(): string
    {
        return "db:table:create";
    }

    public function execute(array $options=[]): void
    {
        // Retrieve Databases config
        $config = $this->launcher->database->getAll();

        // Create STMT
        $connections = new DatabaseConnection($this->launcher->database);

        $dir = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_folder_database'),
        );

        $scan = scandir($dir);

        array_walk($scan, function($entry) use ($dir) {
            if (preg_match("/\.json$/", $entry))
            {
                $file_name  = preg_replace("/\.json$/", "", $entry);
                $file       = $this->path->join($dir, $entry);
                $source     = file_get_contents($file);
                $object     = json_decode($source);
                $table_name = $object->table_name ?? $file_name;

                // var_dump($object);

                // $sql = "";

                $columns = [];

                foreach ($object->columns as $column)
                {
                    $col = "";
                    $name    = $column->name;
                    $type    = $column->type;
                    $isPK    = $column->primary_key ?? false;
                    $isAI    = $column->auto_increment ?? false;
                    $isUniq  = $column->unique ?? false;
                    $default = $column->default ?? false;

                    // Column Name
                    $col = "`{$name}`";

                    // Column Type
                    $col.= " ".strtoupper($type);

                    // Is Primary Key
                    $col.= $isPK !== false ? ' PRIMARY KEY' : '';

                    // Is Auto Increment
                    $col.= $isAI !== false ? ' AUTO_INCREMENT' : '';

                    // Is Auto Increment
                    $col.= $isUniq !== false ? ' UNIQUE' : '';

                    // Has Default 
                    $col.= $default !== false ? ' DEFAULT '.$default : '';

                    // $sql.= ",\n";

                    array_push($columns, $col);
                }

                $sql = sprintf(
                    "CREATE TABLE {$table_name} (%s)", 
                    implode(', ', $columns)
                );

                array_push($this->tables, [
                    'file'       => $file,
                    'source'     => $source,
                    'object'     => $object,
                    'table_name' => $table_name,
                    'sql'        => $sql,
                ]);
            }
        });


        foreach ($config as $name => $info)
        {
            $stmt = $connections->getStmt($name);

            foreach($this->tables as $table) {
                $query = $stmt->prepare( $table['sql'] );
                $query->execute();
    
                // print_r("\n");
                // print_r("\n");
                // print_r("\n");
                // print_r($table['sql']);
                // print_r("\n");
                // print_r("\n");
                // print_r("\n");
            };
        }
        // print_r( $this->launcher->database->getAll() );
        // echo "create tables";
    }
}