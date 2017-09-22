<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PromotionSectionsRepository extends EntityRepository
{
    public function findPromotionSectionsAvailables($status)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('ps')
            ->from('VientoSurAppAppBundle:PromotionSections', 'ps')
            ->where('ps.status = :status')
            ->orderBy('ps.created', 'DESC');

        $qb->setParameter('status', $status);

        return $qb->getQuery()->getResult();
    }
}