<?php

namespace App\Form;

use App\Entity\BookCategorie;
use App\Enum\ExchangeTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre du Livre',
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form'
                ],
            ])
            ->add('author', TextType::class, [
                'required' => false,
                'label' => 'Auteur du Livre',
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form'
                ],
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form'
                ],
            ])
            ->add('exchangeType', EnumType::class, [
                'class' => ExchangeTypeEnum::class,
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Type Echange',
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form',
                    'placeholder' => 'iugiugg'
                ],
            ])
            ->add('bookCategorie', EntityType::class, [
                'class' => BookCategorie::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Catégorie',
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form'
                ],
            ]);
    }
}
