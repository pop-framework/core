<?php 
namespace Pop\Components;

use Pop\Configuration\ConfigurationFactory;
use Pop\Configuration\ConfigurationInterface;

class ComponentFactory extends ConfigurationFactory implements ConfigurationInterface
{
    private array $components = [];

    public function loader(): self
    {
        $this->add(CopyrightComponent::class);

        return $this;
    }

    public function add(string $className): self
    {
        array_push($this->components, $className);

        return $this;
    }

    public function getAll(): array 
    {
        return $this->components;
    }

    public function __call(string $component, array $arguments=[])
    {
        foreach ($this->components as $componentClass)
        {
            if (method_exists($componentClass, 'name'))
            {
                if (strtolower($component) === strtolower($componentClass::name()))
                {
                    return (new $componentClass)->load(...$arguments);
                }
            }
        }
    }
}
