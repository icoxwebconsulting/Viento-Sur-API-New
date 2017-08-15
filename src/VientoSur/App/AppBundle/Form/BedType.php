<?php

namespace VientoSur\App\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VientoSur\App\AppBundle\Entity\Bed;

class BedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'room',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\Room'
                )
            )
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'bedType',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\BedType'
                )
            )
            ->add(
                'quantity',
                TextType::class
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Bed::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_bed';
    }
}