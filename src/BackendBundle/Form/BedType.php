<?php

namespace BackendBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        $id = $options['id'];

        $builder
            ->add(
                'room',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\Room',
                    'query_builder' => function (EntityRepository $er) use ($id){
                        return $er->createQueryBuilder('r')
                            ->where('r.created_by = :id')
                            ->orderBy('r.name', 'ASC')
                            ->setParameter('id', $id);
                    }
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
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 1,
                        'max' => 10
                    )
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Bed::class,
            'id' => null
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