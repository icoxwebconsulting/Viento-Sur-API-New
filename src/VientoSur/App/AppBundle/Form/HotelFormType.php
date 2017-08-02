<?php

namespace VientoSur\App\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use VientoSur\App\AppBundle\Entity\Hotel;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
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
            ->add('percentageGain',
                PercentType::class)
//            ->add(
//                'image',
//                VichImageType::class,
//                array(
//                    'label' => false,
//                    'required' => false
//                )
//            )
            ->add(
                'stars',
                TextType::class
            )
            ->add(
                'hotelTypes',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\HotelType',
                )
            )
//            ->add(
//                'section',
//                EntityType::class,
//                array(
//                    'class' => 'VientoSur\App\AppBundle\Entity\PromotionSections',
//                )
//            )
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