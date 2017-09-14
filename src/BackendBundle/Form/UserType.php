<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'first_name',
                TextType::class,
                array(
                    'label' => false,
                    "mapped" => true,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 32, //Longitud máxima
                    )
                )
            )
            ->add(
                'last_name',
                TextType::class,
                array(
                    'label' => false,
                    "mapped" => true,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 32, //Longitud máxima
                    )
                )
            )
            ->add(
                'username',
                TextType::class,
                array(
                    'label' => false,
                    "mapped" => true,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 32, //Longitud máxima
                    )
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => false,
                    "mapped" => true,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 255, //Longitud máxima
                    )
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VientoSur\App\AppBundle\Entity\User'
        ));
    }


    public function getBlockPrefix()
    {
        return 'user_registration';
    }

}