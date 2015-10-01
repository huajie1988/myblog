<?php

/**
 * Created by PhpStorm.
 * User: Huajie
 * Date: 2015/5/25
 * Time: 17:20
 * 下列引号必须是双引号！
 */

namespace Tarsier\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class user
 * @package Tarsier\HomeBundle\Entity
 * @ORM\Entity(repositoryClass="UserprofileRepository")
 * @ORM\Table(name="userprofile")
 */
class userprofile{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="integer")
     */
    protected $packet;
    /**
     * @ORM\Column(type="string")
     */
    protected $moon;
    /**
     * @ORM\Column(type="integer")
     */
    protected $age;
    /**
     * @ORM\Column(type="integer")
     */
    protected $sex;

    /**
     * @OneToOne(targetEntity="user", mappedBy="profile")
     */
    protected $user;

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
     * Set moon
     *
     * @param string $moon
     * @return userprofile
     */
    public function setMoon($moon)
    {
        $this->moon = $moon;

        return $this;
    }

    /**
     * Get moon
     *
     * @return string 
     */
    public function getMoon()
    {
        return $this->moon;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return userprofile
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set sex
     *
     * @param integer $sex
     * @return userprofile
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return integer 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set user
     *
     * @param \Tarsier\HomeBundle\Entity\user $user
     * @return userprofile
     */
    public function setUser(\Tarsier\HomeBundle\Entity\user $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tarsier\HomeBundle\Entity\user 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set packet
     *
     * @param integer $packet
     * @return userprofile
     */
    public function setPacket($packet)
    {
        $this->packet = $packet;

        return $this;
    }

    /**
     * Get packet
     *
     * @return integer 
     */
    public function getPacket()
    {
        return $this->packet;
    }
}
