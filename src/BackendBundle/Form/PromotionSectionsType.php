<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
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
        return 'vientosur_app_appbundle_promotionsections';
    }
}
