<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoteProductos
 *
 * @ORM\Table(name="lote_productos")
 * @ORM\Entity
 */
class LoteProductos
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
     * @ORM\Column(name="id_lote", type="integer", nullable=false)
     */
    private $idLote;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_prod", type="integer", nullable=false)
     */
    private $idProd;



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
     * Set idLote
     *
     * @param integer $idLote
     * @return LoteProductos
     */
    public function setIdLote($idLote)
    {
        $this->idLote = $idLote;
    
        return $this;
    }

    /**
     * Get idLote
     *
     * @return integer 
     */
    public function getIdLote()
    {
        return $this->idLote;
    }

    /**
     * Set idProd
     *
     * @param integer $idProd
     * @return LoteProductos
     */
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;
    
        return $this;
    }

    /**
     * Get idProd
     *
     * @return integer 
     */
    public function getIdProd()
    {
        return $this->idProd;
    }
}