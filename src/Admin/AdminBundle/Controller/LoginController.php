<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller
{
	//Envia los datos asi mismo para verificar si hubo un intento de logeo.
    public function indexAction(Request $request)
    {        
        $login   = $this->get('login.service');
		$session = new Session();

        //Intento de logeo.
        if ($request->get('user')!=null)
        {
        	//Intento el logeo.
			$resu = $login->login($request->get('user'),$request->get('password'));

			if ($resu!=false)
			{				
				//Traigo el usuario.
				$user = $login->getUserById($resu->getId());

				//Logeo.
				$session->set('sessionUser', $user);

				//Voy al home.				
				return $this->redirect($this->generateUrl('admin_homepage'));
			}
			else			
				return $this->render('AdminBundle:Default:login.html.twig',array('error'=>true));
        }	
       	else
			return $this->render('AdminBundle:Default:login.html.twig');
    }

    //Cuando recibo el logout.
    public function logoutAction()
    {
    	//Borro todos los datos de la sesion.
    	$session = new Session();
    	$session->clear();

		return $this->redirect($this->generateUrl('admin_login'));    	
    }

    //Perfil del usuario.
    public function perfilAction($id)
    {
        //Traigo info del usuario especificado.
        $login   = $this->get('login.service');
        $user    = $login->getUserById($id);

        return $this->render('AdminBundle:Default:perfil.html.twig',array('user'=>$user));
    }
}
