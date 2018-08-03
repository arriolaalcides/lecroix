<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	//Reviso si puedo acceder.
    	if ($this->accesOk())
    	{
			return $this->render('AdminBundle:Default:home.html.twig');
    	}
    	else
    		return $this->redirect($this->generateUrl('admin_login'));    	
    }

    //Reviso si esta logeado el usuario, sino redirecciono al login.
    private function accesOk()
    {
    	$session = new Session();
    	$user 	 = $session->get('sessionUser');

    	//Pude obtener algo de la sesion, si no hay nada vuelvo al login.
    	if ($user==null)
			return false;
		else
			return true;
    }

    //Url del perfil.
    public function perfil($id)
    {
    	
    }
}
