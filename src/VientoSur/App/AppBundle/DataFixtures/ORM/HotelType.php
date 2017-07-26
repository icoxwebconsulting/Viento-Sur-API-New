<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\HotelType as HotelTypeEntity;

class HotelType extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $hotel_types = array(
            'APT',
            'BAD',
            'BTQ',
            'DPT',
            'HOT',
            'HST',
            'MOT',
            'RSR'
        );
        foreach ($hotel_types as $name) {
            $entity = new HotelTypeEntity();
            $entity->setName($name);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}