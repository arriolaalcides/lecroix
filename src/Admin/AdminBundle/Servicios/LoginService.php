<?php

namespace Admin\AdminBundle\Servicios;

use Admin\AdminBundle\Entity\Usuario;
use Admin\AdminBundle\Entity\Destino;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager as Manager;

class LoginService
{
	private $em;
    private $container;
	
    public function __construct($entityManager, $container)
	{
        $this->em 		 = $entityManager;
        $this->container = $container;      
    }

	//Valida si el user y password son correctos.
	public function login($user,$passw)
	{
		//Traigo el repositorio de usuarios.
		$repository = $this->em->getRepository('AdminBundle:Usuario');
		
		//Armo la consulta.
		$query  = $repository->createQueryBuilder('p')
					->where('p.nick = :user')
					->andWhere('p.password = :passw')
					->setParameter('user', $user)
					->setParameter('passw', $passw)
					->getQuery();

		//Traigo datos.
		$resu = $query->getResult();
			
		if ($resu!=null)
		{
			if (count($resu)>0)			
				return $resu[0];
			else
				return false;
		}
		else
			return false;
	}

	//Traigo el usuario en base al id.
	public function getUserById($iduser)
	{
        $con   = $this->em->getConnection();
        $sql   = "select a.id,
                         a.nombre,
                         a.apellido,
                         a.tipo_usu as tipoUsu,
                         b.descripcion as tipo_usu_desc,
                         a.nick,
                         c.descripcion as destino
                  from 	 usuario as a
                  left join tipo_usuario as b on b.id=a.tipo_usu
                  left join destino      as c on c.id=a.destino
                  where  a.id=".$iduser.";";
        
        $query = $con->prepare($sql);
        $query->execute();        
        $resu  = $query->fetchAll();

        if (count($resu)>0)
        	return $resu[0];
        else
        	return null;
    }
}
