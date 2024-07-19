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

class RegistrationFormType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'Aaccepte les termes et conditions',
                'label_attr' => [
                        'class' => 'label-form'
                    ],
                    'attr' => [
                        'class' => 'input-form'
                    ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'attr' => ['autocomplete' => 'new-password',
                'class' => 'input-form'
                ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
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
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
