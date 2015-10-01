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
use Doctrine\ORM\Mapping\ManyToMany;
/**
 * Class user
 * @package Tarsier\HomeBundle\Entity
 * @ORM\Entity(repositoryClass="TagsRepository")
 * @ORM\Table(name="Tags")
 */
class tags{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $name;


    /**
     * @ORM\Column(type="integer")
     */
    protected $click;

    /**
     * @ManyToMany(targetEntity="article", mappedBy="tag")
     **/
    protected $article;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return tags
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set click
     *
     * @param integer $click
     * @return tags
     */
    public function setClick($click)
    {
        $this->click = $click;

        return $this;
    }

    /**
     * Get click
     *
     * @return integer 
     */
    public function getClick()
    {
        return $this->click;
    }

    /**
     * Add article
     *
     * @param \Tarsier\HomeBundle\Entity\article $article
     * @return tags
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
}
