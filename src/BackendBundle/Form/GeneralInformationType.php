<?php

namespace BackendBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Gedmo\Translatable\Translatable;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GeneralInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )    
            )
            ->add(
                'namePt',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )
            )
            ->add(
                'nameEn',
                TextType::class,
                array(
                    'mapped' => false,
                    'required' => false
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VientoSur\App\AppBundle\Entity\GeneralInformation',
            'id' => null,
            'cascade_validation' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_general_information';
    }
}