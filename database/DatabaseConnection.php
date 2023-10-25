<?php 
namespace Pop\Database;

use Pop\Database\DatabaseFactory;

class DatabaseConnection
{
    private array $connections = [];

    /**
     * The current database connection
     *
     * @var string
     */
    protected string $connection;

    /**
     * The current database table
     *
     * @var string
     */
    protected string $table;

    /**
     * The current PDO statement
     *
     * @var \PDO
     */
    protected \PDO $stmt;
    protected string $sql;

    public function __construct(DatabaseFactory $databaseFactory, $dsnWithSchema = true)
    {
        foreach($databaseFactory->getAll() as $name => $settings)
        {
            $dsn  = $this->dsn($settings, $dsnWithSchema);
            $user = $settings['user'];
            $pass = $settings['pass'];

            try {
                $this->connections[$name] = new \PDO($dsn, $user, $pass);
            }
            catch(\PDOException $e) 
            {
                throw new \Exception("Database connection failed for $name",  $e->getCode());
            }
        }

        $this->setConnection();
        $this->setTable();
    }

    public function setConnection(?string $name = null): self
    {
        if (empty($name))
        {
            $name = array_key_first($this->connections);
        }

        $this->connection = $name;
        $this->stmt = $this->connections[$name];

        return $this;
    }
    public function getConnection(): string 
    {
        return $this->connection;
    }
    public function getStmt(string $name): \PDO 
    {
        return $this->connections[$name];
    }

    public function setTable(?string $name = null): self
    {
        if (empty($name))
        {
            $name = get_called_class();
            $name = explode("\\", $name);
            $name = end($name);
            $name = str_replace("Model", '', $name);
            $name = strtolower($name);
        }

        $this->table = $name;

        return $this;
    }

    private function dsn(array $db, bool $dsnWithSchema=true): string
    {     
        $dsn = $db['driver'].":";
        $dsn.= "host=".$db['host'].";";
        $dsn.= "port=".$db['port'].";";

        if ($dsnWithSchema)
        {
            $dsn.= "dbname=".$db['schema'].";";
        }

        if (!empty($db['charset']))
        {
            $dsn.= "charset=".$db['charset'].";";
        }

        return $dsn;
    }
}