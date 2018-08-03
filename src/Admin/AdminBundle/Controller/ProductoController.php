<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\AdminBundle\Entity\Producto;
use Admin\AdminBundle\Entity\LogProductos;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager as Manager;
use Datetime;

class ProductoController extends Controller
{
    public function indexAction($id,Request $request)
    {
        //Reviso si puedo acceder.
        if ($this->accesOk())
        {
            //Si es listar.
            if ($id=='list')
                return $this->listarProducto($id);

            //Si es agregar.
            if ($id=='add')
                return $this->agregarProducto($id,$request);

            //Si viene un id numerico, es editar.
            if (is_numeric($id))            
                return $this->editarProducto($id,$request);            
            
            //Si viene la accion de borrar
            if (strlen($id)>0)
            {
                if ($id[0]=='D')
                    return $this->borrarProducto($id);
            }
            
        }
        else
            return $this->redirect($this->generateUrl('admin_login'));
    }

    // 13/03/2017: Busca productos, modificado su ruta.
    public function buscarProductoAction(Request $request)
    {
        //Reviso si puedo acceder.
        if ($this->accesOk())
        {
            //Traigo el servicio de los codigos de barras.        
            $codebar   = $this->get('codebar.service');
            $prodSrv   = $this->get('productos.service');
            $productos = null;

            if($request->get('buscar_tip_prod')!==-1)
            {
                $desde = $request->get('desde');
                $hasta = $request->get('hasta');
                $tipo = $request->get('buscar_tip_prod');

                //Traigo todos los productos dentro de ese rango.
                $productos = $prodSrv->getAllProductoRangoParam($desde,$hasta,$tipo);
            }        

            return $this->render('AdminBundle:Default:buscarprod.html.twig',array('productos'=>$productos));
        }
        else
            return $this->redirect($this->generateUrl('admin_login'));
    }

    //Dibujo la plantilla.
    public function plantillaCodesAction($idlog)
    {
        $log  = $this->getDoctrine()
                   ->getRepository('AdminBundle:LogProductos')
                   ->find($idlog);                    

        if ($log!=null)
        {
            //Decodifico.
            $productos = $log->getDatos();
            $productos = json_decode($productos);
            
            if ($productos!=null)
                return $this->render('AdminBundle:Default:plantilllaCodes.html.twig',array('productos'=>$productos));
            else
            {
                echo 'No se encontro el log.';
                exit;                
            }
        }
        else
        {
            echo 'No se encontro el log.';
            exit;
        } 
    }

    //Lista todo el lote de productos cargados.
    public function listProductsAction($desde,$hasta,$idlog)
    {
        //Traigo el servicio de los codigos de barras.        
        $codebar   = $this->get('codebar.service');
        $prodSrv   = $this->get('productos.service');

        //Traigo todos los productos dentro de ese rango.
        $productos = $prodSrv->getAllProductoRango($desde,$hasta);

        //Completo la lista de productos agregandle el rango.
        for ($i=0;$i<=count($productos)-1;$i++)
        {
            $prod            = $productos[$i];
            $prod['codebar'] = $codebar->getCode($prod);
            $productos[$i]   = $prod;
        }

        return $this->render('AdminBundle:Default:listprod.html.twig',array('productos'=>$productos,'idlog'=>$idlog));
    }

    //Lista todo los lotes cargados por un usuario.
    public function listCargasProdAction()
    {
        //Traigo el servicio de los codigos de barras.
        $prodSrv = $this->get('productos.service');

        $session = $this->container->get('session');
        $user    = $session->get('sessionUser');

        //Traigo todos los productos dentro de ese rango.
        $productos = $prodSrv->getCargasUser($user['id']);

        return $this->render('AdminBundle:Default:listCargas.html.twig',array('productos'=>$productos,"user"=>$user));
    }    

    //LISTAR
    private function listarProducto($id)
    {
        $stat     = null;
        $resu     = null;
        $prodSrv  = $this->get('productos.service');

        //Traigo todos los Usuarios.
        $resu = $prodSrv->getAllProducto(20);

        return $this->render('AdminBundle:Default:producto.html.twig',array('productos'=>$resu,'id'=>$id,'stat'=>$stat,'mode'=>'list'));
    }

    //AGREGAR
    private function agregarProducto($id,$request)
    {
        $stat      = null;
        $resu      = null;
        $mode      = null;
        $log       = 0;
        $envio     = array();

        $prodSrv      = $this->get('productos.service');
        $destinos     = $prodSrv->getAllDestinos();
        $tipoProducto = $prodSrv->getAllTipoProductos();
        $modelos      = $prodSrv->getAllModelos();
               
        //Si vienen datos del formulario.
        if ($request->get('txt_tipoProd'))
        {
            //Aca se debe validad que venga ingresada la cantidad de articulos de este mismo tipo a crear.
            if ($request->get('txt_cantidad'))
            {
                //Tema fechas.
                if($request->get('txt_fecha')=="")
                    $fecha_fab = new \DateTime('now' - 1);
                else
                    $fecha_fab = \DateTime::createFromFormat('d/m/Y',$request->get('txt_fecha'));

                //Grabo el usuario y el producto.
                $envio    = array($request->get('txt_tipoProd'),
                                  $request->get('txt_modelo'),
                                  $fecha_fab,
                                  $request->get('txt_origen'));

                //Lista de inserts
                $resuList = array();

                //Itero para cargar los productos.
                for ($i=0;$i<=$request->get('txt_cantidad')-1;$i++)
                {
                    //Hago el insert.
                    $resu   = $this->recordProducto($envio);

                    //Agrego.
                    if ($resu!=null)
                        array_push($resuList,$resu);
                }

                //Contadores.
                $min = 0;
                $max = 0;

                //Si hay datos guardados ok los muestro
                if (count($resuList)>0)
                {
                    $session = $this->container->get('session');
                    $user    = $session->get('sessionUser');

                    //Guardo el registro de creación.
                    $log = $prodSrv->saveLogProductos($user['id'],$resuList);

                    //Asigno limites.
                    $min = $resuList[0]->getId();
                    $max = $resuList[count($resuList)-1]->getId();
                }                    

                if ($resu=!null)
                {
                    $stat='ok';
                }
                else
                    $stat = 'error';                
            }
            
            //Voy a la pantalla de resultado de carga.
            return $this->redirect($this->generateUrl('admin_producto_code', array('desde' => $min,'hasta'=>$max,'idlog'=>$log)));
        }
        else
        {
            $mode     = 'add';

            //Renderizo.
            return $this->render('AdminBundle:Default:producto.html.twig',array('productos'=>$resu,'tipoProducto'=>$tipoProducto,'modelos'=>$modelos,'id'=>$id,'stat'=>$stat,'mode'=>$mode,'destinos'=>$destinos));
        }
    }

    //EDITAR
    private function editarProducto($id,$request)
    {
        $stat     = null;
        $resu     = null;
        $mode     = null;

        $prodSrv  = $this->get('productos.service');
        $codebar  = $this->get('codebar.service');        

        //Si vienen los datos del formulario.
        if ($request->get('txt_tipoProd')!=null)
        {
           //Actualizo datos. 
           $em   = $this->getDoctrine()->getManager();
           $resu = $this->getDoctrine()
                        ->getRepository('AdminBundle:Producto')
                        ->find($id);

            $resu->setIdTipoProd($request->get('txt_tipoProd'));
            $resu->setIdModelo($request->get('txt_modelo'));
            $resu->setDestino($request->get('txt_origen'));
            $fecha_fab = new \DateTime($request->get('txt_fecha')); 
            $resu->setFechaFab($fecha_fab);
            $em->flush();

            //Actualizo el codigo de barras.
            $em   = $this->getDoctrine()->getManager();
            $dest = $this->getDoctrine()
                         ->getRepository('AdminBundle:Destino')
                         ->find($resu->getDestino());

            if ($dest!=null)
            {
                //Actualizo el codigo de barra.
                $resu->setCodebar($codebar->getCodeParams($dest->getDescripcion(),
                                                          $resu->getIdTipoProd(),
                                                          $resu->getIdModelo(),
                                                          $resu->getId()));

                $em->flush();
            }

            $mode = 'list';
            $stat = 'ok';

            //Traigo todos los usuarios
            $resu = $prodSrv->getAllProducto();                                
        }
        else
        {
            $resu = $this->getDoctrine()
                         ->getRepository('AdminBundle:Producto')
                         ->find($id);
            $mode = 'edit';                                 
        }

        $tipoProducto = $prodSrv->getAllTipoProductos();
        $modelos      = $prodSrv->getAllModelos();
        $destinos     = $prodSrv->getAllDestinos();        

        //Renderizo.
        return $this->render('AdminBundle:Default:producto.html.twig',array('productos'=>$resu,'tipoProducto'=>$tipoProducto,'modelos'=>$modelos,'id'=>$id,'stat'=>$stat,'mode'=>$mode,'destinos'=>$destinos));
    }

    //BORRAR
    private function borrarProducto($id)
    {
        //Obtengo el id.
        $id = substr($id, 1,strlen($id)-1);

        $em   = $this->getDoctrine()->getManager();
        $resu = $this->getDoctrine()
                     ->getRepository('AdminBundle:Producto')
                     ->find($id);

        if($resu) $em->remove($resu);
        $em->flush();

        $mode = 'list';
        $stat = 'ok';
        
        $prodSrv   = $this->get('productos.service');

        //Traigo todos los destinos
        $productos = $prodSrv->getAllProducto();

        //Renderizo.
        return $this->render('AdminBundle:Default:producto.html.twig',array('productos'=>$productos,'id'=>$id,'stat'=>$stat,'mode'=>$mode));
    }

    //Guardo un nuevo destino.
    private function recordProducto($request)
    {                                   
        $codebar  = $this->get('codebar.service');

        //Creo la instancia de la clase.
        if(!$request[0]==null)
        {
            $prod = new Producto();
            $prod->setIdTipoProd($request[0]);
            $prod->setIdModelo($request[1]);
            $prod->setFechaFab($request[2]);
            $prod->setDestino($request[3]);                    

            //Grabo los datos.
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
            
            //Traigo el destino.
            $em   = $this->getDoctrine()->getManager();
            $dest = $this->getDoctrine()
                         ->getRepository('AdminBundle:Destino')
                         ->find($prod->getDestino());

            if ($dest!=null)
            {
                //Actualizo el codigo de barra.
                $prod->setCodebar($codebar->getCodeParams($dest->getDescripcion(),
                                                         $prod->getIdTipoProd(),
                                                         $prod->getIdModelo(),
                                                         $prod->getId()));

                $em->flush();
            }

            //Nuevo id.
            return $prod;
        }
    }

    //?
    private function setFecha()
    {
        $this->fecha = new \DateTime();
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