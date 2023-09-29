<?php 
namespace Pop\Framework;

use Pop\Framework\Framework;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class FrameworkFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        $documentRoot = $this->launcher->directory;
        $projectRoot = $this->path->join(
            $this->launcher->directory, 
            $this->launcher->support === 'web' ? '../' : '',
        );

        $this->set('document_root', $documentRoot);
        $this->set('project_root', $projectRoot);

        // Add default parameters
        $this->addParameters(Framework::RULES);


        // Import the custom configuration
        // -- 

        // $custom_configuration_file =  $this->path->join(
        //     $this->get('projectRoot'),
        //     $this->get('configuration_file_framework'),
        // );

        // $parameters = $this->import($custom_configuration_file);

        // print_r($parameters);

        return $this;
    }
}
