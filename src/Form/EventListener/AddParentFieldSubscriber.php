<?php

namespace App\Form\EventListener;

use App\Entity\Product\Category;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AddParentFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $category = $event->getData();
        $form = $event->getForm();  
        
        $slug = $category->getSlug();
        
        if (NULL != $category->getName()) {
            
            $form->add('parent', EntityType::class, array(
                'class' => Category::class,
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) use ($slug) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.slug != :slug')
                        ->orderBy('c.name', 'ASC')
                        ->setParameter('slug', $slug)
                    ;
                },
            ));
        }
    }
}