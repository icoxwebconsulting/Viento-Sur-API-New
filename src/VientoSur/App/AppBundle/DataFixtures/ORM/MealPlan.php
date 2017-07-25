<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\MealPlan as MealPlanEntity;

class MealPlan extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $meal_plans = array(
            'D',
            'BRA',
            'BRL',
            'BRC',
            'BRB',
            'M',
            'H',
            'F',
            'A'
        );
        foreach ($meal_plans as $name) {
            $entity = new MealPlanEntity();
            $entity->setName($name);
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}