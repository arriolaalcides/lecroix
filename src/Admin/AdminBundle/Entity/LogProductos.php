<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destino
 *
 * @ORM\Table(name="log_productos")
 * @ORM\Entity
 */
class LogProductos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="idusuario", type="integer", nullable=false)
     */
    private $idusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="datos", type="string")
     */
    private $datos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

  /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="descrip", type="string")
     */
    private $descrip;    
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datos
     *
     * @param string $datos
     * @return Destino
     */
    public function setDatos($datos)
    {
        $this->datos = $datos;
    
        return $this;
    }

    /**
     * Get datos
     *
     * @return string 
     */
    public function getDatos()
    {
        return $this->datos;
    }

    /**
     * Set fecha
     *
     * @param string $fecha
     * @return fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }


    /**
     * Set idusuario
     *
     * @param integer $idusuario
     * @return Destino
     */
    public function setIdusuario($id)
    {
        $this->idusuario = $id;
    
        return $this;
    }

    /**
     * Get idusuario
     *
     * @return integer 
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }
	
    /**
     * Set total
     *
     * @param integer $idusuario
     * @return Destino
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set descrip
     *
     * @param integer $idusuario
     * @return Destino
     */
    public function setDescrip($descrip)
    {
        $this->descrip = $descrip;
    
        return $this;
    }

    /**
     * Get descrip
     *
     * @return integer 
     */
    public function getDescrip()
    {
        return $this->descrip;
    }    
}