<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockProducto
 *
 * @ORM\Table(name="stock_producto")
 * @ORM\Entity
 */
class StockProducto
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
     * @ORM\Column(name="id_prod", type="integer", nullable=false)
     */
    private $idProd;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_lote", type="integer", nullable=false)
     */
    private $idLote;



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
     * @return StockProducto
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
     * Set idProd
     *
     * @param integer $idProd
     * @return StockProducto
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

    /**
     * Set idLote
     *
     * @param integer $idLote
     * @return StockProducto
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
}