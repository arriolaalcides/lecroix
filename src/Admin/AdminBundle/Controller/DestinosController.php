<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\AdminBundle\Entity\Destino;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager as Manager;

class DestinosController extends Controller
{
    public function indexAction($id,Request $request)
    {
        //Reviso si puedo acceder.
        if ($this->accesOk())
        {
            //Si es listar.
            if ($id=='list')
                return $this->listarDestinos($id);

            //Si es agregar.
            if ($id=='add')
                return $this->agregarDestino($id,$request);

            //Si viene un id numerico, es editar.
            if (is_numeric($id))            
                return $this->editarDestino($id,$request);            
            
            //Si viene la accion de borrar
            if (strlen($id)>0)
            {
                if ($id[0]=='D')
                    return $this->borrarDestino($id);
            }
        }
        else
            return $this->redirect($this->generateUrl('admin_login'));
    }

    //LISTAR
    private function listarDestinos($id)
    {
        $stat = null;
        $resu = null;

        //Traigo todos los destinos.
        $resu = $this->getAllDestinos();
        $mode = 'list';

        return $this->render('AdminBundle:Default:destinos.html.twig',array('destinos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));        
    }

    //AGREGAR
    private function agregarDestino($id,$request)
    {
        $stat  = null;
        $resu  = null;
        $mode  = null;
        $users = null;

        //Si vienen datos del formulario.
        if ($request->get('txt_destino')!=null)
        {
            //Grabo el usuario.
            $resu = $this->recordDestino($request->get('txt_destino'),0);

            if ($resu=!null)                    
                $stat='ok';
            else
                $stat = 'error';

            //Traigo todos los destinos
            $resu = $this->getAllDestinos();

            $mode = 'list';
        }
        else
        {
            $mode  = 'add';
            $users = $this->getAllUsers();
        }

        //Renderizo.
        return $this->render('AdminBundle:Default:destinos.html.twig',array('destinos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode,'users'=>$users));
    }

    //EDITAR
    private function editarDestino($id,$request)
    {
        $stat  = null;
        $resu  = null;
        $mode  = null;
        $users = null;

        //Si vienen los datos del formulario.
        if ($request->get('txt_destino')!=null)
        {
           //Actualizo datos. 
           $em   = $this->getDoctrine()->getManager();
           $resu = $this->getDoctrine()
                        ->getRepository('AdminBundle:Destino')
                        ->find($id);

            $resu->setDescripcion($request->get('txt_destino'));
            $resu->setIdUsuEncargado($request->get('txt_user'));
            $em->flush();

            $mode = 'list';
            $stat = 'ok';

            //Traigo todos los destinos
            $resu = $this->getAllDestinos();                    
        }
        else
        {
            $resu = $this->getDoctrine()
                         ->getRepository('AdminBundle:Destino')
                         ->find($id);

            $users = $this->getAllUsers();

            $mode = 'edit';
        }

        //Renderizo.
        return $this->render('AdminBundle:Default:destinos.html.twig',array('destinos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode,'users'=>$users));
    }

    //BORRAR
    private function borrarDestino($id)
    {
        //Obtengo el id.
        $id = substr($id, 1,strlen($id)-1);

        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:Destino')
                     ->find($id);

        $em->remove($resu);
        $em->flush();

        $mode = 'list';
        $stat = 'ok';

        //Traigo todos los destinos
        $resu = $this->getAllDestinos();

        //Renderizo.
        return $this->render('AdminBundle:Default:destinos.html.twig',array('destinos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //Guardo un nuevo destino.
    private function recordDestino($nomb,$encargado)
    {
        $em = $this->getDoctrine()->getManager();

        //Creo la instancia de la clase.
        $dest = new Destino();
        $dest->setDescripcion($nomb);
        $dest->setIdUsuEncargado($encargado);

        //Grabo los datos.
        $em->persist($dest);
        $em->flush();
     
        //Nuevo id.
        return $dest->getId();
    }

    //Traigo todos los usuarios.
    private function getAllUsers()
    {
        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:Usuario')
                     ->findAll();        
        return $resu;
    }

    //Traigo todos los destinos.
    private function getAllDestinos()
    {
        //Traigo todos los destinos cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "SELECT a.id,a.descripcion,a.id_usu_encargado as encargado,b.nick 
                  FROM destino as a 
                  left join usuario as b on b.id=a.id_usu_encargado;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu;    
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
}
