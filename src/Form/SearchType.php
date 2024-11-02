<?php

namespace App\Form;

use App\Entity\BookCategorie;
use App\Enum\ExchangeTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-search-element-input',
                    'placeholder' => 'Titre du Livre',
                ],
            ])
            ->add('author', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-search-element-input',
                    'placeholder' => 'Auteur',
                ],
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-search-element-input',
                    'placeholder' => 'Ville',
                ],
            ])
            ->add('exchangeType', EnumType::class, [
                'class' => ExchangeTypeEnum::class,
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false,
                'placeholder' => 'Type d\'échange',
                'attr' => [
                    'class' => 'form-search-element-input-type',
                ],
            ])
            ->add('bookCategorie', EntityType::class, [
                'class' => BookCategorie::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Catégorie', // Placeholder pour un champ <select>
                'attr' => [
                    'class' => 'form-search-element-input',
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET'
        ]);
    }
}
