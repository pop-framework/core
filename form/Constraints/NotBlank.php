<?php 
namespace Pop\Form\Constraints;

use Pop\Form\Constraint;

/**
 * Validate a not blank constraint
 * 
 * @param string message
 */
class NotBlank extends Constraint
{
    public ?string $message = 'This value should not be blank.';

    public function __construct(array $options = [])
    {
        $this->message = $options['message'] ?? $this->message;
    }

    public function validate(): bool
    {
        return !empty($this->payload);
    }
}