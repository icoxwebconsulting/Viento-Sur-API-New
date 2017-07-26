<?php
namespace VientoSur\App\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use VientoSur\App\AppBundle\Entity\BedType as BedTypeEntity;

class BedType extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bed_types = [
            ['name' => 'Cama individual', 'size' => '90-130 cm de ancho'],
            ['name' => 'Cama doble', 'size' => '131-150 cm de ancho'],
            ['name' => 'Cama doble grande', 'size' => '151-180 de ancho'],
            ['name' => 'Cama doble extragrande', 'size' => '181-210 cm de ancho'],
            ['name' => 'Litera', 'size' => 'Tamaño variable'],
            ['name' => 'Sofá cama', 'size' => 'Tamaño variable'],
            ['name' => 'Futón', 'size' => 'Tamaño variable']
        ];
        $bed_types_en = [
            ['name' => 'Individual bed', 'size' => '90-130 cm wide'],
            ['name' => 'Double bed', 'size' => '131-150 cm wide'],
            ['name' => 'Large double bed', 'size' => '151-180 cm wide'],
            ['name' => 'King size bed', 'size' => '181-210 cm wide'],
            ['name' => 'Bunk bed', 'size' => 'Variable size'],
            ['name' => 'Sofa bed', 'size' => 'Variable size'],
            ['name' => 'Futon', 'size' => 'Variable size']
        ];
        $bed_types_pt = [
            ['name' => 'Cama de solteiro', 'size' => '90-130 cm de largura'],
            ['name' => 'Cama de casal', 'size' => '131-150 cm de largura'],
            ['name' => 'Rainha', 'size' => '151-180 cm de largura'],
            ['name' => 'Duplo king', 'size' => '181-210 cm de largura'],
            ['name' => 'Ancoradouro', 'size' => 'Redimensionável'],
            ['name' => 'Sofá-cama', 'size' => 'Redimensionável'],
            ['name' => 'Futon', 'size' => 'Redimensionável']
        ];
        foreach ($bed_types as $obj)
        {
            $entity = new BedTypeEntity();
            $entity->setName($obj['name']);
            $entity->setSize($obj['size']);
            $manager->persist($entity);
        }
        $manager->flush();

        $types = $manager->getRepository("VientoSurAppAppBundle:BedType")->findAll();
        for ($i = 0; $i < count($types); $i++)
        {
                $types[$i]->setName($bed_types_en[$i]['name']);
                $types[$i]->setSize($bed_types_en[$i]['size']);
                $types[$i]->setTranslatableLocale('en');
                $manager->persist($types[$i]);

            $manager->flush();
                $types[$i]->setName($bed_types_pt[$i]['name']);
                $types[$i]->setSize($bed_types_pt[$i]['size']);
                $types[$i]->setTranslatableLocale('pt');
                $manager->persist($types[$i]);

            $manager->flush();
        }

    }

    public function getOrder()
    {
        return 7;
    }
}