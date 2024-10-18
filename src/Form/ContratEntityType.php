<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\ContratType;
use App\Entity\Facturation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startAt', null, [
                'widget' => 'single_text',
            ])
            ->add('endAt', null, [
                'widget' => 'single_text',
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('status')
            ->add('isDone')
            ->add('type', EntityType::class, [
                'class' => self::class,
                'choice_label' => 'id',
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
            ->add('facture', EntityType::class, [
                'class' => Facturation::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
