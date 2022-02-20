<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('CardNumber',null , ['label' =>' ' ,'required' => true,])
        ->add('Name', null , ['label' =>' ' , 'required' => true])
        
        ->add('Cvv',null ,  ['label' =>' ' , 'required' => true])
            
        ;
    }

    
}
