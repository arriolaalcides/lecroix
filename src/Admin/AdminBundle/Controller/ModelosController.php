<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\AdminBundle\Entity\Modelo;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager as Manager;

class ModelosController extends Controller
{
    public function indexAction($id,Request $request)
    {
        //Reviso si puedo acceder.
        if ($this->accesOk())
        {
            //Si es listar.
            if ($id=='list')
                return $this->listarModelos($id);

            //Si es agregar.
            if ($id=='add')
                return $this->agregarModelo($id,$request);

            //Si viene un id numerico, es editar.
            if (is_numeric($id))            
                return $this->editarModelo($id,$request);

            //Si viene la accion de borrar
            if (strlen($id)>0)
            {
                if ($id[0]=='D')
                    return $this->borrarModelo($id);
            }
        }
        else
            return $this->redirect($this->generateUrl('admin_modelos'));
    }

    //LISTAR
    private function listarModelos($id)
    {
        //Traigo todos los destinos.
        $resu = $this->getAllModelos();
        $mode = 'list';
        $stat = null;

        return $this->render('AdminBundle:Default:modelos.html.twig',array('modelos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));        
    }

    //Traigo todos los destinos.
    private function getAllModelos()
    {
        //Traigo todos los destinos cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "SELECT     a.id,a.descripcion,a.costo_fab,a.precio_venta,a.id_tipo_prod,b.descripcion as tipoProd
                  FROM       modelo        as a
                  inner join tipo_producto as b on b.id = a.id_tipo_prod;";
        
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

    //AGREGAR
    private function agregarModelo($id,$request)
    {
        $stat  = null;
        $resu  = null;
        $mode  = null;
        $tipos = null;

        //Si vienen datos del formulario.
        if ($request->get('txt_modelos')!=null)
        {
            //Grabo el usuario.
            $resu = $this->recordModelo($request);

            if ($resu=!null)                    
                $stat='ok';
            else
                $stat = 'error';

            //Traigo todos los destinos
            $resu = $this->getAllModelos();
            $mode = 'list';
        }
        else
        {
            $mode  = 'add';
            $tipos = $this->getAllTipoProds();
        }

        //Renderizo.
        return $this->render('AdminBundle:Default:modelos.html.twig',array('modelos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode,'tipos'=>$tipos));
    }

    //Traigo todos los usuarios.
    private function getAllTipoProds()
    {
        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:TipoProducto')
                     ->findAll();        
        return $resu;
    }

    //Guardo un nuevo modelo.
    private function recordModelo($request)
    {
        $em = $this->getDoctrine()->getManager();

        //Creo la instancia de la clase.
        $modelo = new Modelo();
        $modelo->setDescripcion($request->get('txt_modelos'));
        $modelo->setCostoFab($request->get('txt_costo_fab'));
        $modelo->setPrecioVenta($request->get('txt_precio_venta'));
        $modelo->setIdTipoProd($request->get('txt_tipo_prod'));

        //Grabo los datos.
        $em->persist($modelo);
        $em->flush();
     
        //Nuevo id.
        return $modelo->getId();
    }

    //EDITAR
    private function editarModelo($id,$request)
    {
        $stat  = null;
        $resu  = null;
        $mode  = null;
        $tipos = null;

        //Si vienen los datos del formulario.
        if ($request->get('txt_modelos')!=null)
        {
           //Actualizo datos. 
           $em   = $this->getDoctrine()->getManager();
           $resu = $this->getDoctrine()
                        ->getRepository('AdminBundle:Modelo')
                        ->find($id);

            $resu->setDescripcion($request->get('txt_modelos'));
            $resu->setCostoFab($request->get('txt_costo_fab'));
            $resu->setPrecioVenta($request->get('txt_precio_venta'));
            $resu->setIdTipoProd($request->get('txt_tipo_prod'));

            $em->flush();

            $mode = 'list';
            $stat = 'ok';

            //Traigo todos los destinos
            $resu = $this->getAllModelos();                  
        }
        else
        {
            $resu = $this->getDoctrine()
                         ->getRepository('AdminBundle:Modelo')
                         ->find($id);

            $tipos   = $this->getAllTipoProds();
            $mode    = 'edit';
        }

        //Renderizo.
        return $this->render('AdminBundle:Default:modelos.html.twig',array('modelos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode,'tipos'=>$tipos));
    }    

    //BORRAR
    private function borrarModelo($id)
    {
        //Obtengo el id.
        $id = substr($id, 1,strlen($id)-1);

        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:Modelo')
                     ->find($id);

        $em->remove($resu);
        $em->flush();

        $mode = 'list';
        $stat = 'ok';

        //Traigo todos los destinos
        $resu = $this->getAllModelos();

        //Renderizo.
        return $this->render('AdminBundle:Default:modelos.html.twig',array('modelos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //Listo los modelos de un tipo de producto.
    public function listModelsTypeAction($id)
    {
        //Traigo todos los destinos cargados en la bd.
        $em    = $this->getDoctrine()->getManager();
        $con   = $em->getConnection();
        $sql   = "select id,descripcion
                  from   modelo
                  where  id_tipo_prod=".$id.";";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();

        echo json_encode($resu);
        exit;
    }
}
