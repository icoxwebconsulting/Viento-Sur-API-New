<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PromotionsRepository extends EntityRepository
{
    public function findPromotionsAvailables()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from('VientoSurAppAppBundle:Promotions', 'p')
            ->where('p.status = 1');
        
        return $qb->getQuery()->getResult();
    }
}