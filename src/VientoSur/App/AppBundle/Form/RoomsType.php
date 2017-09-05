<?php

namespace VientoSur\App\AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use VientoSur\App\AppBundle\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RoomsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'hotel',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\Hotel',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('h')
                            ->orderBy('h.name', 'ASC');
                    }
                )
            )
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'roomCode',
                TextType::class
            )
            ->add(
                'availability',
                TextType::class
            )
            ->add(
                'capacity',
                TextType::class
            )
            ->add(
                'nightlyPrice',
                TextType::class
            )
            ->add('cancellationPolicity',
                TextareaType::class)
            ->add(
                'mealPlan',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\MealPlan'
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Room::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_rooms';
    }
}