<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lote
 *
 * @ORM\Table(name="lote")
 * @ORM\Entity
 */
class Lote
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
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_destino", type="integer", nullable=false)
     */
    private $idDestino;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_usu_alta", type="integer", nullable=false)
     */
    private $idUsuAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_barra", type="string", length=50, nullable=false)
     */
    private $codBarra;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_reg", type="datetime", nullable=false)
     */
    private $fechaReg;



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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Lote
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set idDestino
     *
     * @param integer $idDestino
     * @return Lote
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
     * Set idUsuAlta
     *
     * @param integer $idUsuAlta
     * @return Lote
     */
    public function setIdUsuAlta($idUsuAlta)
    {
        $this->idUsuAlta = $idUsuAlta;
    
        return $this;
    }

    /**
     * Get idUsuAlta
     *
     * @return integer 
     */
    public function getIdUsuAlta()
    {
        return $this->idUsuAlta;
    }

    /**
     * Set codBarra
     *
     * @param string $codBarra
     * @return Lote
     */
    public function setCodBarra($codBarra)
    {
        $this->codBarra = $codBarra;
    
        return $this;
    }

    /**
     * Get codBarra
     *
     * @return string 
     */
    public function getCodBarra()
    {
        return $this->codBarra;
    }

    /**
     * Set fechaReg
     *
     * @param \DateTime $fechaReg
     * @return Lote
     */
    public function setFechaReg($fechaReg)
    {
        $this->fechaReg = $fechaReg;
    
        return $this;
    }

    /**
     * Get fechaReg
     *
     * @return \DateTime 
     */
    public function getFechaReg()
    {
        return $this->fechaReg;
    }
}