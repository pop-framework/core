<?php 
namespace Pop\Error;

use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class ErrorFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        if ($this->launcher->framework->get('environment') != "dev") 
        {
            $this->set('error_reporting', false);
            $this->set('display_errors', false);
            $this->set('display_startup_errors', false);
        }
        else 
        {
            $this->set('error_reporting', E_ALL);
            $this->set('display_errors', true);
            $this->set('display_startup_errors', true);
        }

        return $this;
    }
}
