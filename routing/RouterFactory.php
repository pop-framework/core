<?php 
namespace Pop\Routing;

use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class RouterFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_router'),
        );

        // Import the custom parameters
        $parameters = $this->import($file);

        // Validate & Add custom parameters
        $this
            ->validateParameters(RouterConfig::RULES, $parameters)
            ->addParameters(RouterConfig::RULES, $parameters)
        ;
        
        return $this;
    }

    public function isActiveRoute(string $routeName): bool 
    {
        $current = $this->get('current');
        return $current['name'] === $routeName;
    }
}