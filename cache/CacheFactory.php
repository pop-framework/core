<?php 
namespace Pop\Cache;

use Pop\Cache\CacheSettings;
use Pop\Cache\Commands\CacheClear;
use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class CacheFactory extends ConfigurationFactory implements ConfigurationInterface
{
    public function loader(): self
    {
        // Custom configuration file location
        $file = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $this->launcher->framework->get('configuration_file_cache'),
        );

        // Import the custom parameters
        $parameters = $this->import($file);

        // Validate & Add custom parameters
        $this
            ->validateParameters(CacheSettings::RULES, $parameters)
            ->addParameters(CacheSettings::RULES, $parameters)
        ;

        // Add cache directory to the framework parameters
        $cacheRoot = $this->path->join(
            $this->launcher->framework->get('project_root'),
            $parameters['directory'],
        );
        $this->launcher->framework->set('cache_root', $cacheRoot);


        if ($this->launcher->support === 'cli')
        {
            $this->launcher->cli->addCommand(CacheClear::class);
        }

        return $this;
    }
}