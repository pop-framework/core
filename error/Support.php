<?php 
namespace Pop\Error;

use Pop\Error\ErrorFactory;

class Support 
{
    public function __construct(
        private ErrorFactory $factory,
    ){}


    public function reporting(): self
    {
        $display_errors         = (bool) $this->factory->get('display_errors');
        $display_startup_errors = (bool) $this->factory->get('display_startup_errors');
        $error_reporting        = (int) $this->factory->get('error_reporting');

        ini_set('display_errors', $display_errors);
        ini_set('display_startup_errors', $display_startup_errors);
        error_reporting($error_reporting);

        return $this;
    }
}