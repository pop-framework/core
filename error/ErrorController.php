<?php 
namespace Pop\Error;

use Pop\Controller\AbstractController;

class ErrorController extends AbstractController
{
    public function _404()
    {
        echo "oops";
        // return $this->error(Response::HTTP_NOT_FOUND);
    }
}