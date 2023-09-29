<?php 
namespace Pop\Parameters;

use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class ParametersFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_parameters'),
        );

        // Import the custom parameters
        $parameters = $this->import($file);

        // Add custom parameters
        foreach ($parameters as $name => $value)
        {
            $this->set($name, $value);
        }

        return $this;
    }
}
