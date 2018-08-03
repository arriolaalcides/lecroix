<?php

namespace Admin\AdminBundle\Servicios;

use Admin\AdminBundle\Entity\Usuario;
use Admin\AdminBundle\Entity\Destino;
use Admin\AdminBundle\Entity\Producto;
use Admin\AdminBundle\Entity\TipoProducto;
use Admin\AdminBundle\Entity\Modelo;
use Admin\AdminBundle\Entity\LogProductos;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager as Manager;

class ProductosService
{
	  private $em;
    private $container;
	
    public function __construct($entityManager, $container)
	  {
        $this->em 		   = $entityManager;
        $this->container = $container;      
    }
	
    //Traigo descrip tipo y modelo.
    private function getTipoModelo($idtipo,$idmodelo)
    {
       //Traigo el tipo.       
       $tipo = $this->em->getRepository('AdminBundle:TipoProducto')
                        ->find($idtipo);

       //Traigo el modelo.
       $modelo = $this->em->getRepository('AdminBundle:Modelo')
                          ->find($idmodelo);

        return $tipo->getDescripcion().' - '.$modelo->getDescripcion();
    }

    //Guardo un listado de productos en json.
    public function saveLogProductos($idusuario,$datos)
    {
      //Traigo el servicio de los codigos de barras.
      $codebar = $this->container->get('codebar.service');

    	//Armo un array con id y codigos de barras.
    	$codes = array();

    	foreach ($datos as $prod)
    	{
    		//Vuelvo a array.
    		$prod = array("destino"	  	=> $prod->getDestino(),
    					        "idtipoprod"	=> $prod->getIdTipoProd(),
    					        "idmodelo"	  => $prod->getIdModelo(),
    					        "id"			    => $prod->getId());
			
    		array_push($codes,array("id"		  => $prod['id'],
    								            "codebar"	=> $codebar->getCode($prod)));
    	}

    	//Serializo el array.
    	$codesJson = json_encode($codes);

    	//Creo el objetos.
  		$log = new LogProductos();
  		$log->setIdusuario($idusuario);
  		$log->setDatos($codesJson);
      $log->setTotal(count($codesJson));
  		$log->setFecha(new \DateTime('now'-1));
        
      //Cargo el detalle si hay prods. para ver.
      if (count($codesJson)>0)
      {
        $prod    = $datos[0];        
        $detalle = $this->getTipoModelo($prod->getIdTipoProd(),$prod->getIdModelo());

        $log->setDescrip($detalle);
      }
      else
        $log->setDescrip("Sin detalle");

      //Grabo el log.
      $this->em->persist($log);
      $this->em->flush();
     
      //Nuevo id.
      return $log->getId();
    }

    //Traigo cargas del usuario.
    public function getCargasUser($id)
    {
        //Traigo todos los destinos cargados en la bd.
        $con   = $this->em->getConnection();
        $sql   = "select   id,idusuario,fecha,total,datos,descrip
                  from     log_productos
                  where    idusuario=".$id."
                  order by id desc;";

        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu; 
    }    

    //Traigo todos los productos.
    public function getAllProducto($limit)
    {
        //Traigo todos los destinos cargados en la bd.
        $con   = $this->em->getConnection();
        $sql   = "SELECT p.id,
                         t.id          as idtipoprod,
                         t.descripcion,
                         x.descripcion as modelo,
                         x.id          as idmodelo,
                         p.fecha_fab,
                         d.id          as ididestino,
                         d.descripcion as destino,
                         ''            as codebar
                  FROM producto as p 
                  LEFT JOIN tipo_producto as t on p.id_tipo_prod = t.id
                  left join destino       as d on d.id           = p.destino
                  left join modelo        as x on x.id           = p.id_modelo";

        if ($limit!=null)
          $sql = $sql." order by p.id desc limit ".$limit.";";
        else
          $sql = $sql.";";

        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu;    
    }

    //Traigo todos los productos en un rango desde hasta.    
    public function getAllProductoRango($desde,$hasta)
    {
        //Traigo todos los destinos cargados en la bd.
        $con   = $this->em->getConnection();
        $sql   = "SELECT p.id,
                         t.id          as idtipoprod,
                         t.descripcion,
                         x.descripcion as modelo,
                         x.id          as idmodelo,
                         p.fecha_fab,
                         d.id          as ididestino,
                         d.descripcion as destino,
                         ''            as codebar
                  FROM producto as p 
                  LEFT JOIN tipo_producto as t on p.id_tipo_prod = t.id
                  left join destino       as d on d.id           = p.destino
                  left join modelo        as x on x.id           = p.id_modelo
                  where p.id>=".$desde."
                  and   p.id<=".$hasta.";";

        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu;    
    }     

    // Traigo todos los productos cargados, dependiendo del tipo. 12/03/2017, agregado
    public function getAllProductoRangoParam($desde,$hasta,$tipo)
    {
      //Traigo todos los destinos cargados en la bd.
      if($tipo!==null)
      {
        $con   = $this->em->getConnection();
        $sql   = "SELECT p.id,
                         t.id          as idtipoprod,
                         t.descripcion,
                         x.descripcion as modelo,
                         x.id          as idmodelo,
                         p.fecha_fab,
                         d.id          as ididestino,
                         d.descripcion as destino,
                         ''            as codebar
                  FROM producto as p 
                  LEFT JOIN tipo_producto as t on p.id_tipo_prod = t.id
                  left join destino       as d on d.id           = p.destino
                  left join modelo        as x on x.id           = p.id_modelo ";

        switch ($tipo) {
          case 'id':
            $sql   .= " WHERE p.id BETWEEN ".$desde." AND ".$hasta." ;";
            break;
          
          case 'codbar':
            $sql   .= " WHERE p.codebar>=".$desde." AND  p.codebar<=".$hasta." ;";
            break;

          case 'fecha':
            $fecha1=date("Y-m-d",strtotime($desde));
            $fecha2=date("Y-m-d",strtotime($hasta));
            $sql   .= " WHERE p.fecha_fab BETWEEN '".$desde."' AND '".$hasta."' ;";
            break;
        }
          
          $query = $con->prepare($sql);
          $query->execute();        
          $resu  = $query->fetchAll();
          return $resu;
      }
      else
      {
        return null;
      }
      
    }   

    //Traigo todos los tipos de productos cargados en la bd.
    public function getAllTipoProductos()
    {
        $con   = $this->em->getConnection();
        $sql   = "SELECT id, descripcion                  
                  FROM tipo_producto;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu; 
    }

    //Traigo todos los destinos.
    public function getAllDestinos()
    {
        //Traigo todos los destinos cargados en la bd.
        $con   = $this->em->getConnection();
        $sql   = "SELECT a.id,a.descripcion,a.id_usu_encargado as encargado,b.nick 
                  FROM destino as a 
                  left join usuario as b on b.id=a.id_usu_encargado;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu;    
    }

    //Traigo todos los modelos cargados en la bd.
    public function getAllModelos()
    {        
        $con   = $this->em->getConnection();
        $sql   = "SELECT id, descripcion                  
                  FROM modelo;";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();
        return $resu; 
    }	
}
