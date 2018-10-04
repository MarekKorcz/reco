<?php

namespace App\Form;

use App\Entity\Product\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\EventListener\AddParentFieldSubscriber;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class)
        ;
        
        $builder->addEventSubscriber(new AddParentFieldSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class,
        ));
    }
}