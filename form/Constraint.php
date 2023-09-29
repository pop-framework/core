<?php 
namespace Pop\Form;

abstract class Constraint 
{
    protected mixed $payload;
    public string|null $message;
    // public array $  = false;

    // public function __construct(array $options = [])
    // {
    //     // $this->message = $options['message'] ?? $this->message;
    // }

    // public function setMessage(string $message): self 
    // {
    //     $this->message = $message;

    //     return $this;
    // }

    public function getMessage(): string 
    {
        return $this->message;
    }

    public function message(string $message, array $options=[]): string
    {
        foreach ($options as $key => $value)
        {
            $message = preg_replace("/{{\s?$key\s?}}/", $value, $message);
        }
        
        $this->message = $message;
        return $message;
    }



    public function setPayload(mixed $payload): self 
    {
        $this->payload = $payload;

        return $this;
    }

    public function isInvalid(): bool
    {
        return !$this->validate();
    }
}