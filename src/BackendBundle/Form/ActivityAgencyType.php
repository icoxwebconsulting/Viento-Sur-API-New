<?php

namespace BackendBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VientoSur\App\AppBundle\Entity\ActivityAgency;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use BackendBundle\Form\UserType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ActivityAgencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )    
            )
            ->add(
                'file_number',
                TextType::class,
                array(
                    'label' => false,
                    'required' => false
                )    
            )    
            ->add(
                 'enabled',
                 'checkbox',
                 array(
                    'attr' => array('checked'   => 'checked'),
                )   
            )    
            ->add(
                'address',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )    
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )    
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
                'file_pdf',
                VichImageType::class,
                array(
                    'label' => false,
                    'required' => false
                )
            )    
            ->add('user',
                    new UserType()
            )
            ->add('percentage_vs',  
                  NumberType::class,
                  array(
                    'label' => false,
                    'required' => true
                )   
            )
            ->add('percentage_others',  
                  NumberType::class,
                  array(
                    'label' => false,
                    'required' => true
                )   
            )
            ->add('commercial_discounts',  
                  NumberType::class,
                  array(
                    'label' => false,
                    'required' => true
                )   
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VientoSur\App\AppBundle\Entity\ActivityAgency',
            'id' => null,
            'cascade_validation' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_activity_agency';
    }
}