<?php

namespace App\Form;

use App\Entity\InfosUser;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class InfoUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userName', TextType::class, [
                'label' => 'Nom Utilisateur',
                'required' => true,
                'attr' => [
                    'class' => 'input-form'
                ],
                'label_attr' => [
                    'class' => 'label-form'
                ],
                'constraints' => [
                new NotBlank([
                'message' => 'Le nom d\'utilisateur est obligatoire.',
                ]),
        new Length([
            'min' => 3,
            'max' => 20,
            'minMessage' => 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Le nom d\'utilisateur ne peut pas dépasser {{ limit }} caractères.',
                ]),
        new Regex([
            'pattern' => '/^[a-zA-Z0-9_.-]+$/',
            'message' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, et les caractères spéciaux _ . -',
                ]),
        // Pour la vérification d'unicité, il faut la gérer dans le code (contrôle personnalisé).
                ],

            ])
            ->add('phoneNumber', textType::class, [
                'label' => 'Numéro de Téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'input-form'
                ],
                'label_attr' => [
                    'class' => 'label-form'
                ],
                'constraints' => [
                new NotBlank([
                'message' => 'Le numéro de téléphone est obligatoire.',
                ]),
        new Length([
            'min' => 10,
            'max' => 15,
            'minMessage' => 'Le numéro de téléphone doit contenir au moins {{ limit }} chiffres.',
            'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} chiffres.',
                ]),
        new Regex([
            'pattern' => '/^\+?[0-9]+$/',
            'message' => 'Le numéro de téléphone ne doit contenir que des chiffres et peut commencer par un "+" pour le code pays.',
                ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'address-input',
                    'class' => 'input-form'
                ],
                'constraints' => [
                new NotBlank([
                'message' => 'Veuillez entrer le nom de la ville.',
                ]),
        new Length([
            'min' => 2,
            'max' => 50,
            'minMessage' => 'Le nom de la ville doit comporter au moins {{ limit }} caractères.',
            'maxMessage' => 'Le nom de la ville ne peut pas dépasser {{ limit }} caractères.',
                ]),
                ],
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'date de Naissance',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'address-input',
                    'class' => 'input-form'
                ],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Bio',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'address-input',
                    'class' => 'input-form'
                ],
                'constraints' => [
                new NotBlank([
                'message' => 'Veuillez entrer une biographie.',
                ]),
        new Length([
            'min' => 10,
            'max' => 300,
            'minMessage' => 'La biographie doit comporter au moins {{ limit }} caractères.',
            'maxMessage' => 'La biographie ne peut pas dépasser {{ limit }} caractères.',
                ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfosUser::class,
        ]);
    }
}
