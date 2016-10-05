<?php
/**
 * Created by PhpStorm.
 * User: Yolanda Gonzalez
 * Date: 30/09/16
 * Time: 01:32 PM
 */

//AppBundle/Form/ContactType.php

namespace VientoSur\App\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array(
                'attr' => array(
                    'placeholder' => 'Dime tu nombre'
                )
            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'Email para que pueda responderte'
                )
            ))
            ->add('motivo', 'text', array(
                'attr' => array(
                    'placeholder' => 'El motivo de contactar conmigo'
                )
            ))
            ->add('mensaje', 'textarea', array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'Mensaje que quieres enviarme'
                )
            ))
            ->add('save', 'submit', array('label' => 'Enviar'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'nombre' => array(
                new NotBlank(array('message' => 'El nombre no puede estar vacío.')),
                new Length(array('min' => 3))
            ),
            'email' => array(
                new NotBlank(array('message' => 'El email no puede estar vacío.')),
                new Email(array('message' => 'Email inválido.'))
            ),
            'motivo' => array(
                new NotBlank(array('message' => 'El motivo no puede estar vacío.')),
                new Length(array('min' => 3))
            ),
            'mensaje' => array(
                new NotBlank(array('message' => 'El mensaje no puede estar vacío.')),
                new Length(array('min' => 5))
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'contacto';
    }
}