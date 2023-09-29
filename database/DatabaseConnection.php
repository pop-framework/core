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

    public function __construct(DatabaseFactory $databaseFactory)
    {
        foreach($databaseFactory->getAll() as $name => $settings)
        {
            $dsn  = $settings['dsn'];
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
}