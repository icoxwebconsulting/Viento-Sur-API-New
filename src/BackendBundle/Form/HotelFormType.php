<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use VientoSur\App\AppBundle\Entity\Hotel;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use VientoSur\App\AppBundle\Entity\HotelType;

class HotelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'description',
                TextareaType::class,
                array(
                    'label' => false,
                    'mapped' => true,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                    )
                )
            )
            ->add(
                'address',
                TextType::class
            )
            ->add(
                'latitude',
                HiddenType::class
            )
            ->add(
                'longitude',
                HiddenType::class
            )
            ->add(
                'stars',
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 1,
                        'max' => 5
                    )
                )
            )
            ->add(
                'hotelTypes',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\HotelType'
                )
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Hotel::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_hotel';
    }
}