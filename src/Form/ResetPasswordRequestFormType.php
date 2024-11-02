<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
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
            'constraints' => [
    new NotBlank([
        'message' => 'L\'adresse e-mail est obligatoire.',
    ]),
    new Email([
        'message' => 'Veuillez entrer une adresse e-mail valide.',
    ]),
],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
