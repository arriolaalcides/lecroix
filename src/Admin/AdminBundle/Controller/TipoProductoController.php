<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\AdminBundle\Entity\TipoProducto;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager as Manager;

class TipoProductoController extends Controller
{
    public function indexAction($id,Request $request)
    {
        //Reviso si puedo acceder.
        if ($this->accesOk())
        {
            //Si es listar.
            if ($id=='list')
                return $this->listarTipoProducto($id);

            //Si es agregar.
            if ($id=='add')
                return $this->agregarTipoProducto($id,$request);

            //Si viene un id numerico, es editar.
            if (is_numeric($id))            
                return $this->editarTipoProducto($id,$request);            
            
            //Si viene la accion de borrar
            if (strlen($id)>0)
            {
                if ($id[0]=='D')
                    return $this->borrarTipoProducto($id);
            }
        }
        else
            return $this->redirect($this->generateUrl('admin_login'));
    }

    //LISTAR
    private function listarTipoProducto($id)
    {
        $stat = null;
        $resu = null;

        //Traigo todos los Usuarios.
        $resu = $this->getAllTipoProducto();
        $mode = 'list';

        return $this->render('AdminBundle:Default:tipoproducto.html.twig',array('tipoproducto'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));        
    }

    //AGREGAR
    private function agregarTipoProducto($id,$request)
    {
        $stat = null;
        $resu = null;
        $mode = null;
        $envio= array();
               
        //Si vienen datos del formulario.
        if ($request->get('txt_descripcion'))
        {
            //Grabo el usuario.
           $envio=array($request->get('txt_descripcion'));
            $resu = $this->recordTipoProducto($envio);

            if ($resu=!null)                    
                $stat='ok';
            else
                $stat = 'error';

            //Traigo todos los Usuarios
            $resu = $this->getAllTipoProducto();

            $mode = 'list';
            return $this->redirect($this->generateUrl('admin_tipo_producto', array('id' => $mode)));
        }
        else
        {
            $mode = 'add';
            //Renderizo.
            return $this->render('AdminBundle:Default:tipoproducto.html.twig',array('tipoproducto'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
        }    

    }

    //EDITAR
    private function editarTipoProducto($id,$request)
    {
        $stat = null;
        $resu = null;
        $mode = null;

        //Si vienen los datos del formulario.
        if ($request->get('txt_descripcion')!=null)
        {
           //Actualizo datos. 
           $em   = $this->getDoctrine()->getManager();
           $resu = $this->getDoctrine()
                        ->getRepository('AdminBundle:TipoProducto')
                        ->find($id);

            $resu->setDescripcion($request->get('txt_descripcion'));            
            $em->flush();

            $mode = 'list';
            $stat = 'ok';

            //Traigo todos los usuarios
            $resu = $this->getAllTipoProducto();                                
        }
        else
        {
            $resu = $this->getDoctrine()
                         ->getRepository('AdminBundle:TipoProducto')
                         ->find($id);
            $mode = 'edit';                                 
        }

        //Renderizo.
        return $this->render('AdminBundle:Default:tipoproducto.html.twig',array('tipoproducto'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //BORRAR
    private function borrarTipoProducto($id)
    {
        //Obtengo el id.
        $id = substr($id, 1,strlen($id)-1);

        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:TipoProducto')
                     ->find($id);

        if($resu) $em->remove($resu);
        $em->flush();

        $mode = 'list';
        $stat = 'ok';

        //Traigo todos los tipos de productos
        $tipUsu = $this->getAllTipoProducto();

        //Renderizo.
        return $this->render('AdminBundle:Default:tipoproducto.html.twig',array('tipoproducto'=>$tipUsu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //Guardo un nuevo tipo de producto.
    private function recordTipoProducto($request)
    {                                      
        //Creo la instancia de la clase.
        if(!$request[0]==null)
        {
            $tipUsu = new TipoProducto();
            $tipUsu->setDescripcion($request[0]);            

            //Grabo los datos.
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipUsu);
            $em->flush();
         
            //Nuevo id.
            return $tipUsu->getId();
        }       
        
    }

    //Traigo todos los Usuarios.
    private function getAllTipoProducto()
    {
        //Traigo todos los destinos cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "SELECT id,descripcion
                  FROM tipo_producto;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu;    
    }    

    //Reviso si esta logeado el usuario, sino redirecciono al login.
    private function accesOk()
    {
        $session = new Session();
        $user    = $session->get('sessionUser');

        //Pude obtener algo de la sesion, si no hay nada vuelvo al login.
        if ($user==null)
            return false;
        else
            return true;
    }
}