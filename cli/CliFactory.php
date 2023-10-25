<?php 
namespace Pop\Cli;

use Pop\Cli\Commands\Serve;
use Pop\Launcher;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class CliFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public array $commands = [];
    private string $command;

    public function __construct(
        protected Launcher $launcher,
        private array $options,
    )
    {
        parent::__construct($launcher);
    }

    public function loader(): self
    {
        array_shift($this->options);

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
        $this->prepare();
        $this->proceed();
    }

    private function prepare(): self
    {
        if (!isset($this->options[0]) || $this->options[0] === '?' || strtolower($this->options[0]) === 'help')
        {
            return $this->helper();
        }


        // Get the Command name
        // --

        $this->command = $this->options[0];
        unset($this->options[0]);


        // Get the command options
        // --

        function name(?string $str): string
        {
            $str = preg_replace("/-/", "", $str);
            $str = trim($str);

            return $str;
        }


        if (!empty($this->options))
        {
            $option = null;

            foreach ($this->options as $arg)
            {
                if (preg_match("/^-{1,2}/", $arg))
                {
                    $x = explode('=', $arg);
                    $option = $arg;


                    if (count($x) === 2)
                    {
                        $this->options[name($x[0])] = $x[1];
                    }
                    else if (count($x) === 1)
                    {
                        $this->options[name($x[0])] = true;
                    }
                }
                else 
                {
                    $this->options[name($option)] = $arg;
                    $option = null;
                }

                array_shift($this->options);
            }
        }

        return $this;
    }

    private function proceed(): self
    {
        if (isset($this->commands[$this->command]))
        {
            (new $this->commands[$this->command]($this->launcher))->execute($this->options);
        }
        else 
        {
            echo "\n";
            echo $this->command . " not found";
            echo "\n\n";
        }
        // print_r($command);
        // print_r($this->commands);
        // print_r($this->launcher->cache->getAll());

        return $this;
    }


    private function helper()
    {
        echo "\n";
        echo "TODO: Print POP CLI Helper";
        echo "\n\n";

        return;
    }
}