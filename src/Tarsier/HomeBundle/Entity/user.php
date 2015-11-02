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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class user
 * @package Tarsier\HomeBundle\Entity
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="user")
 */
class user{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $username;
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(min=6,max=30)
     */
    protected $password;
    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $openid;

    /**
     * @ORM\Column(type="text")
     */
    protected $salt;

    /**
     * @ORM\Column(type="text")
     */
    protected $token;

    /**
     * @ORM\Column(type="text")
     */
    protected $sequence;

    /**
     * @ORM\Column(type="string")
     */
    protected $ip;

    /**
     * @ORM\Column(type="integer")
     */
    protected $rememberme;

    /**
     * @ORM\Column(type="string")
     */
    protected $captcha;

    /**
     * @OneToOne(targetEntity="userprofile", inversedBy="user")
     * @JoinColumn("profile_id",referencedColumnName="id")
     */
    protected $profile;

    /**
     * @OneToMany(targetEntity="article", mappedBy="user")
     **/
    protected $article;

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
     * Set username
     *
     * @param string $username
     * @return user
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return user
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return user
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return user
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set openid
     *
     * @param string $openid
     * @return user
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * Get openid
     *
     * @return string 
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * Set profile
     *
     * @param \Tarsier\HomeBundle\Entity\userprofile $profile
     * @return user
     */
    public function setProfile($profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Tarsier\HomeBundle\Entity\userprofile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add article
     *
     * @param \Tarsier\HomeBundle\Entity\article $article
     * @return user
     */
    public function addArticle(\Tarsier\HomeBundle\Entity\article $article)
    {
        $this->article[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Tarsier\HomeBundle\Entity\article $article
     */
    public function removeArticle(\Tarsier\HomeBundle\Entity\article $article)
    {
        $this->article->removeElement($article);
    }

    /**
     * Get article
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return user
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return user
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set sequence
     *
     * @param string $sequence
     * @return user
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return string 
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return user
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set rememberme
     *
     * @param string $rememberme
     * @return user
     */
    public function setRememberme($rememberme)
    {
        $this->rememberme = $rememberme;

        return $this;
    }

    /**
     * Get rememberme
     *
     * @return string
     */
    public function getRememberme()
    {
        return $this->rememberme;
    }

    /**
     * Set captcha
     *
     * @param string $captcha
     * @return user
     */
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;

        return $this;
    }

    /**
     * Get captcha
     *
     * @return string
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Get moon
     *
     * @return string
     */
    public function getMoon(){
        return $this->getProfile()->getMoon();
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge(){
        return $this->getProfile()->getAge();
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex(){
        return $this->getProfile()->getSex();
    }


    /**
     * Set moon
     *
     * @param string $moon
     * @return user
     */
    public function setMoon($moon){
        $this->getProfile()->setMoon($moon);

        return $this;
    }

    /**
     * Set age
     *
     * @param string $age
     * @return user
     */
    public function setAge($age){
        $this->getProfile()->setAge($age);

        return $this;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return user
     */
    public function setSex($sex){
        $this->getProfile()->setSex($sex);
        return $this;
    }

}
