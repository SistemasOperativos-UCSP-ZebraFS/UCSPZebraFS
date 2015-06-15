<?php

namespace UCSPZebraFS\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="realName", type="string", length=255)
     */
    private $realName;

    /**
     * @var string
     *
     * @ORM\Column(name="nameStored", type="string", length=255)
     */
    private $nameStored;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(name="mimetype", type="string", length=255)
     */
    private $mimetype;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="files")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    private $server;


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
     * Set realName
     *
     * @param string $realName
     * @return File
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * Get realName
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Set nameStored
     *
     * @param string $nameStored
     * @return File
     */
    public function setNameStored($nameStored)
    {
        $this->nameStored = $nameStored;

        return $this;
    }

    /**
     * Get nameStored
     *
     * @return string
     */
    public function getNameStored()
    {
        return $this->nameStored;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set server
     *
     * @param string $server
     * @return File
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $mimetype
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    /**
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function __clone()
    {
        $this->id = null;
    }

    public function foo()
    {
        return true;
    }

}