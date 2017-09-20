<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PromotionsRepository extends EntityRepository
{
    public function findPromotionsAvailables($status)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('p')
            ->from('VientoSurAppAppBundle:Promotions', 'p')
            ->where('p.status = :status')
            ->orderBy('p.created', 'ASC');

        $qb->setParameter('status', $status);
        
        return $qb->getQuery()->getResult();
    }
}