<?php

namespace App\Form;

//use App\Entity\Competence;
use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\SearchSkillsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description', TextType::class)
            ->add('min_salaire')
            ->add('max_salaire')
         /*  ->add('competences' , EntityType::class ,[
                'class' => Competence::class ,
                'query_builder' =>function (EntityRepository $er){
                    $query = $er->createQueryBuilder('c');
                    
                    return $query;
                },
                'choice_label' => 'nom',
                'placeholder' => false ,
                'multiple' => true ,
                'mapped' =>false ,
                
            
            ]);*/
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }

   /* public function search_project()
    {
        $form=$this->CreateFormBuilder(null)
            ->add('projet', TextType::class)
            ->add('search', SubmitType::class, [
                'attr'=> [
                    'class'=>'btn btn-primary'
                ]
            ])
            ->getForm();

    }*/
}
