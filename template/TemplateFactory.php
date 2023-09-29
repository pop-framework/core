<?php 
namespace Pop\Template;

use Pop\Template\Template;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class TemplateFactory extends ConfigurationFactory implements ConfigurationInterface 
{
    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_template'),
        );

        // Import the custom parameters
        $parameters = $this->import($file);

        // Validate & Add custom parameters
        $this
            ->validateParameters(Template::RULES, $parameters)
            ->addParameters(Template::RULES, $parameters)
        ;

        // Add the 404 route
        $this->launcher->routes->add([
            'controller'  => "Pop\Error\ErrorController::_404",
            'methods'     => ['HEAD', 'GET'],
            'name'        => "error:404",
            'path'        => "/404",
        ]);

        return $this;
    }
}