<?php 
namespace Pop\Database;

use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class DatabaseFactory extends ConfigurationFactory implements ConfigurationInterface
{
    private array $databases = [];

    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_database'),
        );

        // Import the custom parameters
        $databases = $this->import($file);

        // Validate & Add custom routes
        array_walk($databases, function($database) {$this->add($database);});

        return $this;
    }
    
    public function add(array $database): self
    {
        $name    = $this->name($database);
        $driver  = $this->driver($database);
        $charset = $this->charset($database);
        $host    = $this->host($database);
        $port    = $this->port($database);
        $user    = $this->user($database);
        $pass    = $this->pass($database);
        $schema  = $this->schema($database);
        $prefix  = $this->prefix($database);
        $dsn     = $this->dsn($database);

        $this->databases[$name] = [
            'driver'  => $driver,
            'charset' => $charset,
            'host'    => $host,
            'port'    => $port,
            'user'    => $user,
            'pass'    => $pass,
            'schema'  => $schema,
            'prefix'  => $prefix,
            'dsn'     => $dsn,
        ];

        return $this;
    }
    public function getAll(): array 
    {
        return $this->databases;
    }

    private function name(array $db): string
    {
        return isset($db['name']) && !empty($db['name']) ? $db['name'] : 'main';
    }

    private function driver(array $db): string
    {
        isset($db['driver']) ?: $db['driver'] = "mysql";

        if (!in_array($db['driver'], Database::DRIVERS))
        {
            throw new \Exception("The expected database driver are \"".implode("\", \"", Database::DRIVERS)."\", ".$db['driver']." given for the connection ".$db['name'].".");
        }

        return $db['driver'];
    }

    private function charset(array $db): string
    {
        isset($db['charset']) ?: $db['charset'] = "utf8mb4";

        return $db['charset'];
    }

    private function host(array $db): string
    {
        if (!isset($db['host']))
        {
            throw new \Exception("The host is required for the connection ".$db['name'].".");
        }

        return $db['host'];
    }

    private function port(array $db): int
    {
        isset($db['port']) ?: $db['port'] = 3306;

        return $db['port'];
    }

    private function user(array $db): string
    {
        if (!isset($db['user']) || empty($db['user']))
        {
            throw new \Exception("The user is required for the connection ".$db['name'].".");
        }

        return $db['user'];
    }

    private function pass(array $db): string
    {
        isset($db['pass']) ?: $db['pass'] = "";

        return $db['pass'];
    }

    private function schema(array $db): string
    {
        if (!isset($db['schema']) || empty($db['schema']))
        {
            throw new \Exception("The schema is required for the connection ".$db['name'].".");
        }

        return $db['schema'];
    }

    private function prefix(array $db): string
    {
        isset($db['prefix']) ?: $db['prefix'] = "";

        return $db['prefix'];
    }

    private function dsn(array $db): string
    {     
        $dsn = $db['driver'].":";
        $dsn.= "host=".$db['host'].";";
        $dsn.= "port=".$db['port'].";";
        $dsn.= "dbname=".$db['schema'].";";

        if (!empty($db['charset']))
        {
            $dsn.= "charset=".$db['charset'].";";
        }

        return $dsn;
    }
}
