<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ActivityRepository extends EntityRepository
{
    public function findByLatAndLgn($lat, $lgn){
        
        $config = $this->getEntityManager()->getConfiguration();
        
        $config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');

        $config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');

        $config->addCustomNumericFunction('RADIANS', 'DoctrineExtensions\Query\Mysql\Radians');

        $config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin'); 
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('a, ( 6371 * acos(cos(radians('.$lat.')) * cos(radians(a.latitude_destination)) * cos(radians(a.longitude_destination) - radians('.$lgn.')) + sin(radians('.$lat.')) * sin(radians(a.latitude_destination)))) AS distance ')
            ->from('VientoSurAppAppBundle:Activity', 'a')
            ->having('distance < 70')
            ->orderBy('distance', 'DESC');
        
        echo $qb->getQuery()->getSQL();
        exit();
        
        
        
        /*$qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('a.*, ( 6371 * acos(cos(radians(-31.97648)) * cos(radians(`latitude_destination`)) * cos(radians(`longitude_destination`) - radians(-64.558996)) + sin(radians(-31.97648)) * sin(radians(`latitude_destination`)))) AS distance ')
            ->from('VientoSurAppAppBundle:Activity', 'a')
            ->having('distance < 70')
            ->orderBy('distance', 'DESC');
        
        echo $qb->getQuery()->getSQL();
        exit();
        
        return $qb->getQuery()->getResult();*/
    }
}