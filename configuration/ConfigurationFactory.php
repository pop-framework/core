<?php 
namespace Pop\Configuration;

use Pop\FileSystem\Path;
use Pop\Launcher;

abstract class ConfigurationFactory
{
    protected Path $path;
    
    private array $parameters = [];
    // public array $commands = [];
    
    public function __construct(
        protected Launcher $launcher,
    ){
        $this->path = new Path;
        $this->loader();
    }

    /**
     * Set a new config parameter
     *
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function set(string $name, mixed $value): self
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * Get a config parameter
     *
     * @param string $name
     * @return void
     */
    public function get(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    /**
     * Get all config parameters
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->parameters;
    }


    /**
     * Import custom config file
     *
     * @param string $file
     * @param boolean $required
     * @return array
     */
    protected function import(string $file, bool $required=false): array
    {
        // Check if config file exists
        if (!file_exists($file) && $required)
        {
            throw new \Exception("Can not find the file $file");
        }
        else if (!file_exists($file) && !$required)
        {
            return [];
        }

        // Include the config
        return include_once $file;
    }

    /**
     * Set all parameters
     *
     * @param array $defaults
     * @param array $customs
     * @return self
     */
    protected function addParameters(array $defaults, array $customs = []): self
    {
        foreach ($defaults as $name => $options)
        {
            $this->set($name, $customs[$name] ?? $options['default']);
        }

        return $this;
    }

    /**
     * Validate a parameter
     *
     * @param array $defaults
     * @param array $customs
     * @return self
     */
    protected function validateParameters(array $defaults, array $customs): self
    {
        foreach ($customs as $name => $value)
        {
            // Throw exception if the parameter name is not valid
            if (!isset( $defaults[$name] ))
            {
                $expected = implode("\", \"", array_keys($defaults));

                throw new \Exception(printf("Expected parameters are \"%s\". \"%s\" given.",
                    $expected,
                    $name
                ));
            }

            // Throw exception if the parameter type is not expected
            if (gettype($value) !== $defaults[$name]['type'])
            {
                throw new \Exception( printf("Expected type of %s, %s given", $defaults[$name]['type'], gettype($value)) );
            }
        }

        return $this;
    }

}