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
 * Class articleimg
 * @package Tarsier\HomeBundle\Entity
 * @ORM\Entity(repositoryClass="ArticleImgRepository")
 * @ORM\Table(name="articleimg")
 */
class articleimg{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="integer")
     */
    protected $article_id;
    /**
     * @ORM\Column(type="text")
     */
    protected $front_cover;
    /**
     * @ORM\Column(type="text")
     */
    protected $thumb;

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


    /**
     * Set article_id
     *
     * @param integer $articleId
     * @return articleimg
     */
    public function setArticleId($articleId)
    {
        $this->article_id = $articleId;

        return $this;
    }

    /**
     * Get article_id
     *
     * @return integer 
     */
    public function getArticleId()
    {
        return $this->article_id;
    }
}
