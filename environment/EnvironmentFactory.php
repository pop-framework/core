<?php 
namespace Pop\Environment;

use Pop\Environment\Environment;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class EnvironmentFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_environment'),
        );

        // Import the custom parameters
        $parameters = $this->import($file);

        // Validate & Add custom parameters
        $this
            ->validateParameters(Environment::RULES, $parameters)
            ->addParameters(Environment::RULES, $parameters)
        ;

        // Set the real environment to the Framework parameters
        $this->setRealEnvironment();

        return $this;
    }

    private function setRealEnvironment()
    {
        $environment = $this->get('environment');
        $dev_domains = $this->get('dev_domains');

        if ($environment === "auto")
        {
            $environment = !empty($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], $dev_domains) 
                ? "dev" 
                : "prod"
            ;
        }

        $this->launcher->framework->set('environment', $environment);
    }
}