<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\AdminBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager as Manager;

class UsuarioController extends Controller
{
    public function indexAction($id,Request $request)
    {
        //Reviso si puedo acceder.
        if ($this->accesOk())
        {
            //Si es listar.
            if ($id=='list')
                return $this->listarUsuario($id);

            //Si es agregar.
            if ($id=='add')
                return $this->agregarUsuario($id,$request);

            //Si viene un id numerico, es editar.
            if (is_numeric($id))            
                return $this->editarUsuario($id,$request);            
            
            //Si viene la accion de borrar
            if (strlen($id)>0)
            {
                if ($id[0]=='D')
                    return $this->borrarUsuario($id);
            }
        }
        else
            return $this->redirect($this->generateUrl('admin_login'));
    }

    //LISTAR
    private function listarUsuario($id)
    {
        $stat = null;
        $resu = null;

        //Traigo todos los Usuarios.
        $resu = $this->getAllUsuarios();
        $mode = 'list';

        return $this->render('AdminBundle:Default:usuario.html.twig',array('usuarios'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));        
    }

    //AGREGAR
    private function agregarUsuario($id,$request)
    {
        $stat = null;
        $resu = null;
        $mode = null;
        $envio= array();

        $tipoUsuarios = $this->getAllTipoUsuarios();
        $destinos = $this->getAllDestinos();
               
        //Si vienen datos del formulario.
        if ($request->get('txt_nombre'))
        {
            //Grabo el usuario.
           $envio=array($request->get('txt_nombre'),
                    $request->get('txt_apellido'),
                    $request->get('txt_tipoUsu'),
                    $request->get('txt_nick'),
                    $request->get('txt_password'),
                    $request->get('txt_destino'));
            $resu = $this->recordUsuario($envio);

            if ($resu=!null)                    
                $stat='ok';
            else
                $stat = 'error';

            //Traigo todos los Usuarios
            $resu = $this->getAllUsuarios();

            $mode = 'list';
            return $this->redirect($this->generateUrl('admin_usuario', array('id' => 'list')));
        }
        else
        {
            $mode = 'add';
            //Renderizo.
            return $this->render('AdminBundle:Default:usuario.html.twig',array('usuarios'=>$resu,'tipoUsuarios'=>$tipoUsuarios,'destinos'=>$destinos,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
        }    

    }

    //EDITAR
    private function editarUsuario($id,$request)
    {
        $stat = null;
        $resu = null;
        $mode = null;

        //Si vienen los datos del formulario.
        if ($request->get('txt_nombre')!=null)
        {
           //Actualizo datos. 
           $em   = $this->getDoctrine()->getManager();
           $resu = $this->getDoctrine()
                        ->getRepository('AdminBundle:Usuario')
                        ->find($id);

            $resu->setNombre($request->get('txt_nombre'));
            $resu->setApellido($request->get('txt_apellido'));
            $resu->setTipoUsu($request->get('txt_tipoUsu'));
            $resu->setNick($request->get('txt_nick'));
            $resu->setPassword($request->get('txt_password'));
            $resu->setDestino($request->get('txt_destino'));
            $em->flush();

            $mode = 'list';
            $stat = 'ok';

            //Traigo todos los usuarios
            $resu = $this->getAllUsuarios();                                
        }
        else
        {
            $resu = $this->getDoctrine()
                         ->getRepository('AdminBundle:Usuario')
                         ->find($id);
            $mode = 'edit';                                 
        }

        $tipoUsuarios = $this->getAllTipoUsuarios();
        $destinos = $this->getAllDestinos();

        //Renderizo.
        return $this->render('AdminBundle:Default:usuario.html.twig',array('usuarios'=>$resu,'tipoUsuarios'=>$tipoUsuarios,'destinos'=>$destinos,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //BORRAR
    private function borrarUsuario($id)
    {
        //Obtengo el id.
        $id = substr($id, 1,strlen($id)-1);

        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:Usuario')
                     ->find($id);

        if($resu) $em->remove($resu);
        $em->flush();

        $mode = 'list';
        $stat = 'ok';

        //Traigo todos los destinos
        $usuarios = $this->getAllUsuarios();

        //Renderizo.
        return $this->render('AdminBundle:Default:usuario.html.twig',array('usuarios'=>$usuarios,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //Guardo un nuevo destino.
    private function recordUsuario($request)
    {                                      
        //Creo la instancia de la clase.
        if(!$request[0]==null)
        {
            $usu = new Usuario();
            $usu->setNombre($request[0]);
            $usu->setApellido($request[1]);
            $usu->setTipoUsu($request[2]);
            $usu->setNick($request[3]);
            $usu->setPassword($request[4]);
            $usu->setDestino($request[5]);

            //Grabo los datos.
            $em = $this->getDoctrine()->getManager();
            $em->persist($usu);
            $em->flush();
         
            //Nuevo id.
            return $usu->getId();
        }       
        
    }

    //Traigo todos los Usuarios.
    private function getAllUsuarios()
    {
        //Traigo todos los destinos cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "SELECT u.id, u.nombre, u.apellido
                  ,t.descripcion,u.nick, u.password 
                  ,(select descripcion from destino where id=u.destino) as destino
                  FROM usuario as u 
                  LEFT JOIN tipo_usuario as t on u.tipo_usu=t.id;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu;    
    }

    private function getAllTipoUsuarios()
    {
        //Traigo todos los tipos de usuarios cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "SELECT id, descripcion                  
                  FROM tipo_usuario;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu; 
    }

    private function getAllDestinos()
    {
        //Traigo todos los tipos de usuarios cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "SELECT id, descripcion                  
                  FROM destino;";
        
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