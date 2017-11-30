<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\HotelChain as HotelChainEntity;

class HotelChain extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $hotel_chain = array(
            'Ibis',
            'Sheraton',
            'Park Hyatt',
            'Gran Meliá Hotels & Resorts',
            'NH Hotels',
            'Four Seasons',
            'Dazzler',
            'Esplendor',
            'NH Collection',
            'Amérian',
            'Rochester',
            'Cyan Hoteles',
            'Alvarez Arguelles Hoteles',
            'Howard Johnson'
        );
        foreach ($hotel_chain as $name) {
            $entity = new HotelChainEntity();
            $entity->setName($name);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}