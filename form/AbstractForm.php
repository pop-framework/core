<?php
namespace Pop\Form;

abstract class AbstractForm
{
    const DEFAULT_PROPERTY = [
        'default'     => null,
        'constraints' => [],
        // 'required' => true,
    ];

    protected array $properties = [];
    protected array $errors = [];
    protected $entity;

    public function __construct($entity=null)
    {
        $this->entity = $entity;
        $this->builder();
    }

    protected function add(string $name, array $options): self
    {
        if (isset($this->entity->$name))
        {
            $options['default'] = $this->entity->$name;
        }

        $this->properties[$name] = array_merge(
            self::DEFAULT_PROPERTY, 
            $options, 
            ['name' => $name]
        );

        return $this;
    }

    private function addError($property, $message): self
    {
        $this->errors[$property] = $message;

        return $this;
    }

    public function getErrors(): object
    {
        return !empty($this->errors) 
            ? json_decode(json_encode($this->errors)) 
            : new \stdClass
        ;
    }

    public function submitted(): bool
    {
        if ($submitted = $_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->setData();
        }

        return $submitted;
    }

    private function setData()
    {
        foreach (array_keys($this->properties) as $property)
        {
            $this->properties[$property]['value'] = isset($_POST[$property]) ? trim($_POST[$property]) : null;
        }
    }

    public function getData(): object
    {
        $data = [];

        foreach ($this->properties as $property => $options)
        {
            $data[$property] = $options['value'] ?? $options['default'];
        }

        return json_decode(json_encode($data));
    }

    public function isValid(): bool
    {
        $isValid = true;
        foreach ($this->properties as $property)
        {
            foreach ($property['constraints'] as $type => $constraint)
            {
                $constraint->setPayload($property['value']);

                // var_dump($constraint->isInvalid());
                if ($constraint->isInvalid())
                {
                    $this->addError($property['name'], $constraint->getMessage());
                    $isValid = false;
                    break;
                }
            }
        }

        return $isValid;
    }



    // public function save()
    // {
    //     print_r( $this->entity->id );
    //     print_r( $this->getData() );
    //     exit;
    // }
}
