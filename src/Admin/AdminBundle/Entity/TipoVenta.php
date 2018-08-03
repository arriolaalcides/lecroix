<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoVenta
 *
 * @ORM\Table(name="tipo_venta")
 * @ORM\Entity
 */
class TipoVenta
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="interes", type="decimal", nullable=false)
     */
    private $interes;



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
     * Set descripcion
     *
     * @param string $descripcion
     * @return TipoVenta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set interes
     *
     * @param string $interes
     * @return TipoVenta
     */
    public function setInteres($interes)
    {
        $this->interes = $interes;
    
        return $this;
    }

    /**
     * Get interes
     *
     * @return string 
     */
    public function getInteres()
    {
        return $this->interes;
    }
}