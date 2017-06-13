<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PromotionSectionsRepository extends EntityRepository
{
    public function findPromotionSectionsAvailables()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('ps')
            ->from('VientoSurAppAppBundle:PromotionSections', 'ps')
            ->where('ps.status = 1');

        return $qb->getQuery()->getResult();
    }
}