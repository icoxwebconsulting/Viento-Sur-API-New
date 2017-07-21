<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\Amenity as AmenityEntity;

class Amenity extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $amenities = array(
            'AIR',
            'ACOM',
            'ADUON',
            'AIRTRAN',
            'ASAD',
            'BABYSIT',
            'BAG',
            'BAR',
            'BEASAL',
            'BREAKFST',
            'BREEXCH',
            'BSNSC',
            'CAMA',
            'CAR',
            'CFGU',
            'DISC',
            'DRCLSE',
            'ETES',
            'ELEV',
            'GAMES',
            'GARD',
            'GIM',
            'GOLF',
            'HAIRROOM',
            'HOT',
            'INTCA',
            'INTGR',
            'INGRAH',
            'INTPUAREXCH',
            'INV',
            'ISV',
            'JCZHTL',
            'LASESU',
            'LIPA',
            'LOCKS',
            'LUGADCO',
            'LUGFR',
            'MAPCEL',
            'MASSAGE',
            'MEETROOM',
            'MNA',
            'NPA',
            'OUSWPOSE',
            'PAREEXCH',
            'PAREFR',
            'PARKADCO',
            'PARKING',
            'PEALAC',
            'PET',
            'PISC',
            'PISCCLI',
            'PISCINF',
            'PISCN',
            'PISDESC',
            'R24',
            'RECEP',
            'RESTO',
            'ROOMSVC',
            'SALJUE',
            'SAUN',
            'SEC24',
            'SECTNI',
            'SERCAM',
            'SERCON',
            'SERGUAR',
            'SERNIN',
            'SHKI',
            'SMPEALAC',
            'SMPEALFR',
            'SOL',
            'SOMBR',
            'SPA',
            'STORE',
            'TEM',
            'TENIS',
            'TLS',
            'TOASAB',
            'TRANSER',
            'TVROOM',
            'VALETPRK',
            'WAKEUP'
        );
        foreach ($amenities as $name) {
            $entity = new AmenityEntity();
            $entity->setName($name);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}