<?php 
namespace Pop\Cli;

use Pop\Cli\Commands\Serve;
use Pop\Launcher;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class CliFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public array $commands = [];

    public function __construct(
        protected Launcher $launcher,
        private array $args,
    )
    {
        parent::__construct($launcher);
    }

    public function loader(): self
    {
        array_shift($this->args);

        if ($this->launcher->support === 'cli')
        {
            $this->addCommand(Serve::class);
        }

        return $this;
    }

    public function addCommand(string $class): self
    {
        $this->commands[$class::getName()] = $class;

        return $this;
    }

    public function execute()
    {
        $command = $this->args[0];

        if (isset($this->commands[$command]))
        {
            (new $this->commands[$command]($this->launcher))->execute();
        }
        else 
        {

        echo "\n";
        echo $command . " not found";
        echo "\n\n";
        }
        // print_r($command);
        // print_r($this->commands);
        // print_r($this->launcher->cache->getAll());
    }
}