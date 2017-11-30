<?php

namespace VientoSur\App\AppBundle\Repository;


class RoomRepository extends \Doctrine\ORM\EntityRepository
{

    public function findRoomsByIds($rooms)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('r')
            ->from('VientoSurAppAppBundle:Room', 'r');

        for ($i = 0; $i< count($rooms); $i++){
            $qb->orWhere('r.id = :id'.$i)
                ->setParameter('id'.$i, $rooms[$i]->room_id);
        }

        return $qb->getQuery()->getResult();
    }

    public function findRoomAvailables($hotelId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('r')
            ->from('VientoSurAppAppBundle:Room', 'r')
            ->andWhere('r.hotel = :hotel');

        $qb->setParameter('hotel', $hotelId);

    }
}