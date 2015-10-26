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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * Class user
 * @package Tarsier\HomeBundle\Entity
 * @ORM\Entity(repositoryClass="ArticleRepository")
 * @ORM\Table(name="article")
 */
class article{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $content;
    /**
     * @ORM\Column(type="string")
     */
    protected $front_cover;
    /**
     * @ORM\Column(type="string")
     */
    protected $thumb;
    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="integer")
     */
    protected $create_time;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort;

    /**
     * @ORM\Column(type="integer")
     */
    protected $click;

    /**
     * @ORM\Column(type="integer")
     */
    protected $top;

    /**
     * @ManyToMany(targetEntity="tags", inversedBy="article")
     * @JoinTable(name="article_tags")
     **/
    protected $tag;

    /**
     * @ManyToOne(targetEntity="user", inversedBy="article")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
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
     * Set title
     *
     * @param string $title
     * @return article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return article
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
     * Set create_time
     *
     * @param integer $createTime
     * @return article
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;

        return $this;
    }

    /**
     * Get create_time
     *
     * @return integer 
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return article
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set click
     *
     * @param integer $click
     * @return article
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
     * Set top
     *
     * @param integer $top
     * @return article
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return integer 
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set tag
     *
     * @param integer $tag
     * @return article
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return integer 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set user
     *
     * @param \Tarsier\HomeBundle\Entity\user $user
     * @return article
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
     * Constructor
     */
    public function __construct()
    {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tag
     *
     * @param \Tarsier\HomeBundle\Entity\tags $tag
     * @return article
     */
    public function addTag(\Tarsier\HomeBundle\Entity\tags $tag)
    {
        $this->tag[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Tarsier\HomeBundle\Entity\tags $tag
     */
    public function removeTag(\Tarsier\HomeBundle\Entity\tags $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Set front_cover
     *
     * @param string $frontCover
     * @return article
     */
    public function setFrontCover($frontCover)
    {
        $this->front_cover = $frontCover;

        return $this;
    }

    /**
     * Get front_cover
     *
     * @return string 
     */
    public function getFrontCover()
    {
        return $this->front_cover;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return article
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Get thumb
     *
     * @return string 
     */
    public function getThumb()
    {
        return $this->thumb;
    }
}
