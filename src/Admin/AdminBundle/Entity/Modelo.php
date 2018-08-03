<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo
 *
 * @ORM\Table(name="modelo")
 * @ORM\Entity
 */
class Modelo
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
     * @ORM\Column(name="costo_fab", type="decimal", nullable=false)
     */
    private $costoFab;

    /**
     * @var string
     *
     * @ORM\Column(name="precio_venta", type="decimal", nullable=false)
     */
    private $precioVenta;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_prod", type="integer", nullable=false)
     */
    private $idTipoProd;



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
     * @return Modelo
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
     * Set costoFab
     *
     * @param string $costoFab
     * @return Modelo
     */
    public function setCostoFab($costoFab)
    {
        $this->costoFab = $costoFab;
    
        return $this;
    }

    /**
     * Get costoFab
     *
     * @return string 
     */
    public function getCostoFab()
    {
        return $this->costoFab;
    }

    /**
     * Set precioVenta
     *
     * @param string $precioVenta
     * @return Modelo
     */
    public function setPrecioVenta($precioVenta)
    {
        $this->precioVenta = $precioVenta;
    
        return $this;
    }

    /**
     * Get precioVenta
     *
     * @return string 
     */
    public function getPrecioVenta()
    {
        return $this->precioVenta;
    }

    /**
     * Set idTipoProd
     *
     * @param integer $idTipoProd
     * @return Modelo
     */
    public function setIdTipoProd($idTipoProd)
    {
        $this->idTipoProd = $idTipoProd;
    
        return $this;
    }

    /**
     * Get idTipoProd
     *
     * @return integer 
     */
    public function getIdTipoProd()
    {
        return $this->idTipoProd;
    }
}