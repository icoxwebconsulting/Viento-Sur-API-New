<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\PaymentType as PaymentTypeEntity;

class PaymentType extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $payment_types = array(
            'at_destination'
        );
        foreach ($payment_types as $name) {
            $entity = new PaymentTypeEntity();
            $entity->setName($name);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}