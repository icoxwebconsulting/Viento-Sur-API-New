<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;

class ActivityRepository extends EntityRepository
{
    public function findByLatAndLgn($lat, $lgn){
        
        $config = $this->getEntityManager()->getConfiguration();
        
        $config->addCustomNumericFunction('COS', 'DoctrineExtensions\Query\Mysql\Cos');

        $config->addCustomNumericFunction('ACOS', 'DoctrineExtensions\Query\Mysql\Acos');

        $config->addCustomNumericFunction('RADIANS', 'DoctrineExtensions\Query\Mysql\Radians');

        $config->addCustomNumericFunction('SIN', 'DoctrineExtensions\Query\Mysql\Sin');
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('a, a.id AS id,'
                . ' a.name AS name,'
                . ' a.latitude_destination AS latitude,'
                . ' a.longitude_destination AS longitude,'
                . ' a.price AS price,'
                . ' a.address_destination AS address_destination,'
                . ' ( 6371 * acos(cos(radians('.$lat.')) * cos(radians(a.latitude_destination)) * cos(radians(a.longitude_destination) - radians('.$lgn.')) + sin(radians('.$lat.')) * sin(radians(a.latitude_destination)))) AS distance ')
            ->from('VientoSurAppAppBundle:Activity', 'a') 
            ->where("a.availability = 1")    
            ->having('distance < 5');

        $query = $qb->getQuery();
 
        $query->setHint(
        Query::HINT_CUSTOM_OUTPUT_WALKER,
        'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, 'es');

        return $query;
    }
}