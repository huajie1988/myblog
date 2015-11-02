<?php

namespace Tarsier\HomeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RssRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RssRepository extends EntityRepository
{

    public function findAllRss(){
        $dql="SELECT r FROM TarsierHomeBundle:rss r ORDER BY r.id DESC";
        $query=$this->getEntityManager()->createQuery($dql);
        return $query;
    }

}
