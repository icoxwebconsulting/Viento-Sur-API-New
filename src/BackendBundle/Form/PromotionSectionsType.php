<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VientoSur\App\AppBundle\Entity\PromotionSections;
use VientoSur\App\AppBundle\Entity\Status;

class PromotionSectionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('subtitle')
            ->add(
                'status',
                EntityType::class,
                array(
                    'class' => 'VientoSur\App\AppBundle\Entity\Status',
                )
            )
            ->add(
                'titlePt',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )
            )
            ->add(
                'titleEn',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )
            )
            ->add(
                'subtitlePt',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )
            )
            ->add(
                'subtitleEn',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )
            );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PromotionSections::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_promotionsections';
    }
}
