<?php 
namespace Pop\Security;

use Pop\Security\Security;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class SecurityFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_security'),
        );

        // Import the custom parameters
        $parameters = $this->import($file);

        // Validate & Add custom parameters
        $this
            ->validateParameters(Security::RULES, $parameters)
            ->addParameters(Security::RULES, $parameters)
        ;


        $this->launcher->routes->add([
            'controller'  => "Pop\Security\SecurityController::logout",
            'methods'     => ['HEAD', 'GET'],
            'name'        => "logout",
            'path'        => "/logout",
        ]);

        return $this;
    }
}
