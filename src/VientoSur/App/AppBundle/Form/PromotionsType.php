<?php

namespace VientoSur\App\AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VientoSur\App\AppBundle\Entity\Promotions;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PromotionsType extends AbstractType
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
                'link',
                UrlType::class
            )
            ->add(
                'image',
                VichImageType::class,
                array(
                    'label' => false,
                    'required' => false
                )
            )
            ->add(
                'status',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\Status',
                )
            )
            ->add(
                'section',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\PromotionSections',
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VientoSur\App\AppBundle\Entity\Promotions'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_promotions';
    }
}