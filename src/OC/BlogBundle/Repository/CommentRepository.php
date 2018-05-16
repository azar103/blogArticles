<?php

namespace OC\BlogBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    
	public function getCommentsWithArticle($limit)
	{
        $qb = $this->createQueryBuilder('com')
                   ->innerJoin('com.article','a')
                   ->addSelect('a')
                    ;

        $qb->setMaxResults($limit);
        


        return $qb->createQuery()
                  ->getResult();

                              


	}
}