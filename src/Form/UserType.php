<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance')
            ->add('description')
            ->add('profession')
            ->add('address')
            ->add('code_postal')
       
            ->add('competences' , EntityType::class ,[
                'class' => Competence::class ,
                'query_builder' =>function (EntityRepository $er){
                    $query = $er->createQueryBuilder('c');
                    
                    return $query;
                },
                'choice_label' => 'nom',
                'placeholder' => false ,
                'multiple' => true ,
                'mapped' =>false ,
                
            
            ])
            ->add('photo',FileType::class, array(
                'label'=>'Photo',
                'attr'=>[
                    'placeholder'=>'Photo',
                    'mapped'=>false,
                    

                ],
                'data_class' => null

            ))
            ->add('Save', SubmitType::class ,[
                'attr' => [
                    'class'=>'post_jp_btn',
                    'label'=>'Save'
                ]
                
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
