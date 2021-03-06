<?php

namespace Tarsier\HomeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function checkUserValid($username,$password){
      $dql =  $this->getEntityManager()
          ->createQueryBuilder()
          ->select('u.id,u.username,u.password,u.salt,u.status')
          ->from('TarsierHomeBundle:user','u')
          ->where("u.username=:username")
          ->getDQL();

     $ret = $this->getEntityManager()
         ->createQuery($dql)
          ->setParameter("username",$username)
          ->setMaxResults(1)
          ->getResult();


     if(empty($ret))
         return false;

     $ret=$ret[0];

    if(intval($ret['status'])<2)
        return false;

     if(md5($password.$ret['salt'])==$ret['password'])
         return true;

     return false;
    }

    public function findAllUser(){
        $dql="SELECT u FROM TarsierHomeBundle:user u ORDER BY u.id DESC";
        $query=$this->getEntityManager()->createQuery($dql);
        return $query;
    }

    public function checkIssetUser($username,$email){
        $dql =  $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u.id,u.username,u.password,u.salt,u.status')
            ->from('TarsierHomeBundle:user','u')
            ->where("u.username=:username OR u.email=:email")
            ->getDQL();

        $ret = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter("username",$username)
            ->setParameter("email",$email)
            ->getResult();

        return count($ret);
    }

}
