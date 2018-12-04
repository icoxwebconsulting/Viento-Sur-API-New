<?php

namespace BackendBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use VientoSur\App\AppBundle\Entity\GeneralInformation;
use \VientoSur\App\AppBundle\Entity\ActivityAgency;

class ActivityType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $rol = $options['rol'];
        
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
            )
            ->add(
                'description',
                TextareaType::class,
                array(
                    'label' => false,
                    'mapped' => true,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 1000
                    )
                )
            )
            ->add(
                'descriptionPt',
                TextareaType::class,
                array(
                    'mapped' => false,
                    'required' => false,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 1000
                    )
                )
            )
            ->add(
                'descriptionEn',
                TextareaType::class,
                array(
                    'mapped' => false,
                    'required' => false,
                    'attr' => array(
                        "minlength" => 1, //Longitud minima
                        "maxlength" => 1000
                    )
                )
            )
            ->add(
                'address_origin',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )  
            )
            ->add(
                'latitude_origin',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )  
            )
            ->add(
                'longitude_origin',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )  
            )  
            ->add(
                'address_destination',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )  
            )
            ->add(
                'latitude_destination',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )  
            )
            ->add(
                'longitude_destination',
                TextType::class,
                array(
                    'label' => false,
                    'required' => true
                )  
            )    
            ->add(
                'price',
                TextType::class
            )    
            ->add(
                 'availability',
                 'checkbox',
                 array(
                    'required' => false
                )   
            )
            ->add(
                 'monday',
                 'checkbox',
                 array(
                    'required' => false, 
                )   
            )  
            ->add(
                 'tuesday',
                 'checkbox',
                 array(
                    'required' => false, 
                )   
            )   
            ->add(
                 'wednesday',
                 'checkbox',
                 array(
                    'required' => false, 
                )   
            )  
            ->add(
                 'thursday',
                 'checkbox',
                 array(
                    'required' => false, 
                )   
            )   
            ->add(
                 'friday',
                 'checkbox',
                 array(
                    'required' => false, 
                )   
            )   
            ->add(
                 'saturday',
                 'checkbox',
                 array(
                    'required' => false, 
                )   
            ) 
            ->add(
                 'sunday',
                 'checkbox',
                 array(
                    'required' => false,                     
                )   
            )  
            ->add(
                 'am',
                 'checkbox',
                 array(
                    'required' => false, 
                )    
            )
            ->add(
                 'pm',
                 'checkbox',
                 array(
                    'required' => false, 
                )    
            )  
            ->add(
                 'all_day',
                 'checkbox',
                 array(
                    'required' => false, 
                )    
            )   
            ->add(
                 'several_day',
                 'checkbox',
                 array(
                    'required' => false, 
                )    
            )       
            ->add(
                'capacity_for_shift',
                ChoiceType::class,
                array(
                    'choices' => ['1'=>'1',
                                  '2'=>'2',
                                  '3'=>'3',
                                  '4'=>'4',
                                  '5'=>'5',
                                  '6'=>'6',
                                  '7'=>'7',
                                  '8'=>'8',
                                  '9'=>'9',
                                  '10'=>'10',
                                  '11'=>'11',
                                  '12'=>'12',
                                  '13'=>'13',
                                  '14'=>'14',
                                  '15'=>'15',
                                  '16'=>'16',
                                  '17'=>'17',
                                  '18'=>'18',
                                  '19'=>'19',
                                  '20'=>'20',]
                )
            )
            ->add(
                'duration',
                ChoiceType::class,
                array(
                    'choices' => ['1'=>'1',
                                  '2'=>'2',
                                  '3'=>'3',
                                  '4'=>'4',
                                  '5'=>'5',
                                  '6'=>'6',
                                  '7'=>'7',
                                  '8'=>'8',
                                  '9'=>'9',
                                  '10'=>'10',
                                  '11'=>'11',
                                  '12'=>'12',
                                  '13'=>'13',
                                  '14'=>'14',
                                  '15'=>'15',
                                  '16'=>'16',
                                  '17'=>'17',
                                  '18'=>'18',
                                  '19'=>'19',
                                  '20'=>'20',]
                )
            )  
            ->add(
                'pick_up_am',
                ChoiceType::class,
                array(
                    'choices' => ['00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00']
                )
            )
            ->add(
                'from_am',
                ChoiceType::class,
                array(
                    'choices' => ['00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00']
                )
            )  
            ->add(
                'to_am',
                ChoiceType::class,
                array(
                    'choices' => ['00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00']
                )
            )    
            ->add(
                'pick_up_pm',
                ChoiceType::class,
                array(
                    'choices' => ['13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            ) 
            ->add(
                'from_pm',
                ChoiceType::class,
                array(
                    'choices' => ['13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            )
            ->add(
                'to_pm',
                ChoiceType::class,
                array(
                    'choices' => ['13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            ) 
            ->add(
                'from_all',
                ChoiceType::class,
                array(
                    'choices' => [
                                  '00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00',  
                                  '13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            )
            ->add(
                'to_all',
                ChoiceType::class,
                array(
                    'choices' => ['00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00',  
                                  '13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            )
            ->add(
                'pick_up_all',
                ChoiceType::class,
                array(
                    'choices' => ['00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00',  
                                  '13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            )
            ->add(
                'pick_up_several',
                ChoiceType::class,
                array(
                    'choices' => ['00:00'=>'00:00',
                                  '01:00'=>'01:00',  
                                  '02:00'=>'02:00',
                                  '03:00'=>'03:00',
                                  '04:00'=>'04:00',
                                  '05:00'=>'05:00',
                                  '06:00'=>'06:00',
                                  '07:00'=>'07:00',
                                  '08:00'=>'08:00',
                                  '09:00'=>'09:00',
                                  '10:00'=>'10:00',
                                  '11:00'=>'11:00',
                                  '12:00'=>'12:00',  
                                  '13:00'=>'13:00',
                                  '14:00'=>'14:00',  
                                  '15:00'=>'15:00',
                                  '16:00'=>'16:00',
                                  '17:00'=>'17:00',
                                  '18:00'=>'18:00',
                                  '19:00'=>'19:00',
                                  '20:00'=>'20:00',
                                  '21:00'=>'21:00',
                                  '22:00'=>'22:00',
                                  '23:00'=>'23:00',]
                )
            ) 
            ->add(
                'from_several',
                TextType::class,
                array(
                    'label' => false,
                    'required' => false
                )    
            )    
            ->add(
                'to_several',
                TextType::class,
                array(
                    'label' => false,
                    'required' => false
                )    
            )        
            ->add('general_information' , EntityType::class , array(
                      'class'    => GeneralInformation::class ,
                      'query_builder' => function (EntityRepository $er) {
                                        return $er->createQueryBuilder('gi')
                                            ->orderBy('gi.name', 'ASC');
                      },  
                      'multiple' => true , )
            );
             
            if($rol===0){        
                $builder->add(
                    'activity_agency',
                    EntityType::class,
                    array(
                          'class'    => ActivityAgency::class ,
                          'query_builder' => function (EntityRepository $er) {
                                            return $er->createQueryBuilder('ag')
                                                ->orderBy('ag.name', 'ASC');
                          })
                );
            }
                      
                      
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VientoSur\App\AppBundle\Entity\Activity',
            'id' => null,
            'cascade_validation' => true
        ));
        $resolver->setRequired('rol'); // Requires that currentOrg be set by the caller.
        $resolver->setAllowedTypes('rol',['int']); // Validates the type(s) of option(s) passed.
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_activity';
    }
}