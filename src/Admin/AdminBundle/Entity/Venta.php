<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Venta
 *
 * @ORM\Table(name="venta")
 * @ORM\Entity
 */
class Venta
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
     * @ORM\Column(name="id_destino", type="integer", nullable=false)
     */
    private $idDestino;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_usu", type="integer", nullable=false)
     */
    private $idUsu;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", nullable=false)
     */
    private $precio;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_venta", type="integer", nullable=false)
     */
    private $tipoVenta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_venta", type="datetime", nullable=false)
     */
    private $fechaVenta;



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
     * Set idDestino
     *
     * @param integer $idDestino
     * @return Venta
     */
    public function setIdDestino($idDestino)
    {
        $this->idDestino = $idDestino;
    
        return $this;
    }

    /**
     * Get idDestino
     *
     * @return integer 
     */
    public function getIdDestino()
    {
        return $this->idDestino;
    }

    /**
     * Set idUsu
     *
     * @param integer $idUsu
     * @return Venta
     */
    public function setIdUsu($idUsu)
    {
        $this->idUsu = $idUsu;
    
        return $this;
    }

    /**
     * Get idUsu
     *
     * @return integer 
     */
    public function getIdUsu()
    {
        return $this->idUsu;
    }

    /**
     * Set precio
     *
     * @param string $precio
     * @return Venta
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precio
     *
     * @return string 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set tipoVenta
     *
     * @param integer $tipoVenta
     * @return Venta
     */
    public function setTipoVenta($tipoVenta)
    {
        $this->tipoVenta = $tipoVenta;
    
        return $this;
    }

    /**
     * Get tipoVenta
     *
     * @return integer 
     */
    public function getTipoVenta()
    {
        return $this->tipoVenta;
    }

    /**
     * Set fechaVenta
     *
     * @param \DateTime $fechaVenta
     * @return Venta
     */
    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;
    
        return $this;
    }

    /**
     * Get fechaVenta
     *
     * @return \DateTime 
     */
    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }
}