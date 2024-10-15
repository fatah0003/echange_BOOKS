<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'input-form'
                ],
                'label' => 'Adresse e-mail',
                'label_attr' => [
                    'class' => 'label-form'
                ],
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            //     'label' => 'Aaccepte les termes et conditions',
            //     'label_attr' => [
            //             'class' => 'label-form'
            //         ],
            //         'attr' => [
            //             'class' => 'input-form'
            //         ],
            // ])
            
            ->add('infosUser', InfoUserType::class)
            ->add('password', PasswordType::class, [
                'mapped' => false, // Ce champ n'est pas lié à une propriété de l'entité User
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe',
                    ]),
                ],
                'label' => 'Entrez votre mot de passe',
                'attr' => [
                    'class' => 'input-form',
                ],
                'label_attr' => [
                    'class' => 'label-form',
                ],
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

// class UserType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('email')
//             ->add('roles', ChoiceType::class, [
//                 'choices'  => [
//                     'Admin' => 'ROLE_ADMIN',
//                     'User' => 'ROLE_USER',
//                 ],
//                 'multiple' => true, // Car c'est un tableau
//                 'expanded' => true, // Si tu veux afficher des cases à cocher
//             ])
//             ->add('password')
//             ->add('infosUser', EntityType::class, [
//                 'class' => InfosUser::class,
// 'choice_label' => 'id',
//             ])
//             ->add('favorites', EntityType::class, [
//                 'class' => Books::class,
// 'choice_label' => 'id',
// 'multiple' => true,
//             ])
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => User::class,
//         ]);
//     }
// }
