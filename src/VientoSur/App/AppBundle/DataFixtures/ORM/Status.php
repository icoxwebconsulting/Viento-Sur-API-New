<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\Status as StatusEntity;

class Status extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $statuses = array(
            'Activo',
            'Borrador',
            'Bloqueado',
            'Inactivo'
        );
        foreach ($statuses as $name) {
            $entity = new StatusEntity();
            $entity->setName($name);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}