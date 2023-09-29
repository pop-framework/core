<?php 
namespace Pop\Security;

use Pop\Controller\AbstractController;

class SecurityController extends AbstractController
{
    public function logout()
    {
        session_destroy();
        header("location: /");
        exit;
    }
}