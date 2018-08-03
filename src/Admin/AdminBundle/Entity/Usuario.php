<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 */
class Usuario
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
     * @ORM\Column(name="nombre", type="string", length=50, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=50, nullable=false)
     */
    private $apellido;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo_usu", type="boolean", nullable=false)
     */
    private $tipoUsu;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=100, nullable=true)
     */
    private $nick;

	 /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=20, nullable=true)
     */
    private $password;

     /**
     * @var integer
     *
     * @ORM\Column(name="destino", type="integer", nullable=false)
     */
    private $destino;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set tipoUsu
     *
     * @param boolean $tipoUsu
     * @return Usuario
     */
    public function setTipoUsu($tipoUsu)
    {
        $this->tipoUsu = $tipoUsu;
    
        return $this;
    }

    /**
     * Get tipoUsu
     *
     * @return boolean 
     */
    public function getTipoUsu()
    {
        return $this->tipoUsu;
    }

    /**
     * Set nick
     *
     * @param string $nick
     * @return Usuario
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    
        return $this;
    }

    /**
     * Get nick
     *
     * @return string 
     */
    public function getNick()
    {
        return $this->nick;
    }
	
    /**
     * Set password
     *
     * @param string $nick
     * @return Usuario
     */
    public function setPassword($passw)
    {
        $this->password = $passw;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->nick;
    }	

    /**
     * Set destino
     *
     * @param string $destino
     * @return Usuario
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    
        return $this;
    }

    /**
     * Get destíno
     *
     * @return string 
     */
    public function getDestino()
    {
        return $this->destino;
    }       
}