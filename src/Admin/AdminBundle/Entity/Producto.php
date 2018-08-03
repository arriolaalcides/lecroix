<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity
 */
class Producto
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
     * @ORM\Column(name="id_tipo_prod", type="integer", nullable=false)
     */
    private $idTipoProd;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="destino", type="integer", nullable=false)
     */
    private $destino;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id_modelo", type="integer", nullable=false)
     */
    private $idModelo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fab", type="datetime", nullable=false)
     */
    private $fechaFab;

    /**
     * @var Codebar
     *
     * @ORM\Column(name="codebar", type="string")
     */
    private $codebar;


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
     * Set idTipoProd
     *
     * @param integer $idTipoProd
     * @return Producto
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

    /**
     * Set idModelo
     *
     * @param integer $idModelo
     * @return Producto
     */
    public function setIdModelo($idModelo)
    {
        $this->idModelo = $idModelo;
    
        return $this;
    }

    /**
     * Get idModelo
     *
     * @return integer 
     */
    public function getIdModelo()
    {
        return $this->idModelo;
    }    

    /**
     * Get destino
     *
     * @return integer 
     */
    public function getDestino()
    {
        return $this->destino;
    }

	/**
     * Set destino
     *
     * @param integer $idModelo
     * @return Producto
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    
        return $this;
    }

    /**
     * Set destino
     *
     * @param integer $idModelo
     * @return Producto
     */
    public function setCodebar($code)
    {
        $this->codebar = $code;
    
        return $this;
    }

    /**
     * Get idModelo
     *
     * @return integer 
     */
    public function getCodebar()
    {
        return $this->codebar;
    }

	
    /**
     * Set fechaFab
     *
     * @param \DateTime $fechaFab
     * @return Producto
     */
    public function setFechaFab($fechaFab)
    {
        $this->fechaFab = $fechaFab;
    
        return $this;
    }

    /**
     * Get fechaFab
     *
     * @return \DateTime 
     */
    public function getFechaFab()
    {
        return $this->fechaFab;
    }
}