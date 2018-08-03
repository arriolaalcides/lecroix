<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

//Ejemplo url:
//http://127.0.0.1/pagos/Alcides/lecroixGit/repo5/web/app_dev.php/codebar/0000011111111111100/code128/80/s/


class CodeController extends Controller
{
    public function indexAction($code,$type,$size,$print)
    {        
        $login  = $this->get('codebar.service');

        if ($print=='s')
        	$print = true;
        else
        	$print = false;

        $login->generar($code,$type,$size,$print);
        exit;
    }

    public function generateAction(Request $request)
    {
        echo '1234';
        exit;    	
    }
}
