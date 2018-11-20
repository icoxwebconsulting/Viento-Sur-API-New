<?php

namespace VientoSur\App\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;

class ActivityRepository extends EntityRepository
{
    public function findByLatAndLgn($lat, $lgn, $from_price, $to_price, $day_1, $day_2, $day_3, $day_4, $day_5, $day_6, $day_7, $available, $duration, $locale){
        
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
            ->andWhere('a.price >= '.$from_price)
            ->andWhere('a.price <= '.$to_price)
            ->having('distance < 70');
            
            if($day_1){
                $qb->andWhere('a.sunday = '.$day_1);
            }
            
            if($day_2){
                $qb->andWhere('a.monday = '.$day_2);
            }
            
            if($day_3){
                $qb->andWhere('a.tuesday = '.$day_3);
            }
            
            if($day_4){
                $qb->andWhere('a.wednesday = '.$day_4);
            }
            
            if($day_5){
                $qb->andWhere('a.thursday = '.$day_5);
            }
            
            if($day_6){
                $qb->andWhere('a.friday = '.$day_6);
            }
            
            if($day_7){
                $qb->andWhere('a.saturday = '.$day_7);
            }
        
            if($available){
                switch ($available) {
                    case 'am':
                        $qb->andWhere('a.am = 1');
                    break;
                    case 'pm':
                        $qb->andWhere('a.pm = 1');
                    break;
                    case 'all':
                        $qb->andWhere('a.all_day = 1');
                    break;

                }
            }
        
        $query = $qb->getQuery();
 
        $query->setHint(
        Query::HINT_CUSTOM_OUTPUT_WALKER,
        'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);

        return $query;
    }
}