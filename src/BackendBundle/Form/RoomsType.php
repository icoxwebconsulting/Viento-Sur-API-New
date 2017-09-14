<?php

namespace BackendBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        $id = $options['id'];

        $builder
            ->add(
                'hotel',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\Hotel',
                    'query_builder' => function (EntityRepository $er) use ($id) {
                        return $er->createQueryBuilder('h')
                            ->where('h.created_by = :id')
                            ->orderBy('h.name', 'ASC')
                            ->setParameter('id', $id);
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
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 1,
                        'max' => 1000
                    )
                )
            )
            ->add(
                'capacity',
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 1,
                        'max' => 10
                    )
                )
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
            'data_class' => Room::class,
            'id' => null
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