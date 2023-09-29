<?php 
namespace Pop\Form\Constraints;

use Pop\Form\Constraint;

/**
 * Validate a range constraint
 * 
 * @param int min
 * @param int max
 * @param string minMessage
 * @param string maxMessage
 * @param string notInRangeMessage
 */
class Range extends Constraint
{
    public $min = null;
    public $max = null;
    public string $notInRangeMessage = 'This value should be between {{ min }} and {{ max }}.';
    public string $minMessage = 'This value should be {{ limit }} or more.';
    public string $maxMessage = 'This value should be {{ limit }} or less.';

    public function __construct(array $options = [])
    {
        $this->notInRangeMessage = $options['notInRangeMessage'] ?? $this->notInRangeMessage;
        $this->minMessage = $options['minMessage'] ?? $this->minMessage;
        $this->maxMessage = $options['maxMessage'] ?? $this->maxMessage;
        $this->min = $options['min'] ?? $this->min;
        $this->max = $options['max'] ?? $this->max;
    }

    public function validate(): bool
    {
        if (filter_var($this->payload, FILTER_VALIDATE_FLOAT) === false)
        {
            if ($this->min !== null && $this->max !== null)
            {
                $this->message($this->notInRangeMessage, [
                    'min' => $this->min,
                    'max' => $this->max,
                ]);
            }
            else if ($this->min !== null)
            {
                $this->message($this->minMessage, [
                    'limit' => $this->min,
                ]);
            }
            else if ($this->max !== null)
            {
                $this->message($this->maxMessage, [
                    'limit' => $this->max,
                ]);
            }

            return false;
        }
        else if ($this->min !== null && $this->payload <= $this->min)
        {
            $this->message($this->minMessage, [
                'limit' => $this->min,
            ]);

            return false;
        }
        else if ($this->max !== null && $this->payload >= $this->max)
        {
            $this->message($this->maxMessage, [
                'limit' => $this->max,
            ]);

            return false;
        }
        
        return true;
    }
}