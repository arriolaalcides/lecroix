<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destino
 *
 * @ORM\Table(name="destino")
 * @ORM\Entity
 */
class Destino
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
     * @var integer
     *
     * @ORM\Column(name="id_usu_encargado", type="integer", nullable=false)
     */
    private $idUsuEncargado;



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
     * @return Destino
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
     * Set idUsuEncargado
     *
     * @param integer $idUsuEncargado
     * @return Destino
     */
    public function setIdUsuEncargado($idUsuEncargado)
    {
        $this->idUsuEncargado = $idUsuEncargado;
    
        return $this;
    }

    /**
     * Get idUsuEncargado
     *
     * @return integer 
     */
    public function getIdUsuEncargado()
    {
        return $this->idUsuEncargado;
    }
}