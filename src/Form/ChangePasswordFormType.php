<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Regex;


class ChangePasswordFormType extends AbstractType
{
    use RegexTrait;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => [
                'attr' => ['autocomplete' => 'new-password',
            'class' => 'input-form'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ne doit pas etre vide....',
                    ]),
                    new Regex(self::STRONG_PASSWORD, message: 'Le mot de passe doit contenir au minimum huit caractères, avec au moins une lettre majuscule, une lettre minuscule, un chiffre, et un caractère spécial (#?!@$ %^&*-_).'
)
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'label-form'
                ],
            ],
            'second_options' => [
                'attr' => ['autocomplete' => 'new-password',
            'class' => 'input-form'
                ],
                'label' => 'Confirmer le mot de passe',
                'label_attr' => [
                    'class' => 'label-form'
                ],
            ],
            'invalid_message' => 'The password fields must match.',
            'mapped' => false,

        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
