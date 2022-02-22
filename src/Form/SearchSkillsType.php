<?php

namespace App\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSkillsType extends AbstractType
{
    private $em;

    public function __construct (EntityManagerInterface $em )
    {
        $this->em=$em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function (Collection $value): array {

                return $value->map(fn($d) => (string)$d->getId())->toArray();
            },
            function (array $ids) use ($options): Collection {
                if(empty($ids)){
                    return new ArrayCollection([]);
                }
               return new ArrayCollection(
                    $this->$this->em->getRepository($options['class'])->findBy(['id'=>$ids])
                );
            } 
        ));
    }

   public function configureOptions(OptionsResolver $resolver)
   {
       $resolver->setRequired('class');
       $resolver->setDefaults([
           'compound' => false ,
           'multiple' => true ,
       ]);
   }

   public function buildView(FormView $view, FormInterface $form, array $options)
   {
       dd($form->getData());
       $view->vars['expanded'] = false;
       $view->vars['placeholder'] = null;
       $view->vars['placeholder_in_choices'] = false;
       $view->vars['multiple'] = true;
       $view->vars['preferred_choices'] = [];
       $view->vars['choices'] = $this->choices($form->getData());
       $view->vars['choice_translation_domain'] = false;
       $view->vars['full_name'] .= '[]';
   }
   
   public function getBlockPrefix()
   {
       return 'choice';
   }


   public function choices (Collection $value){
    return $value
    ->map(fn($d) => new ChoiceView($d, (string) $d->getId(), (string) $d))
    ->toArray();
}


}