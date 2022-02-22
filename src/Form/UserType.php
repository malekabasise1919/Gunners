<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
          //  ->add('verified')
           // ->add('code')
          //  ->add('created_at')
           // ->add('banned')
            //->add('messages')
            ->add('competences')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
