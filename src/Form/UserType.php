<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('infosUser', InfoUserType::class);

        // Si l'admin modifie son propre profil ou si l'utilisateur n'est pas admin
        if (!$options['is_admin'] || $options['is_modifying_own_profile']) {
            $builder->add('password', PasswordType::class, [
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
            ]);
        }

        // Ajouter le champ roles uniquement si l'utilisateur est admin
        if ($options['is_admin'] && !$options['is_modifying_own_profile']) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true, // Utilise des cases à cocher
                'label' => 'Rôles',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_admin' => false, // Valeur par défaut
            'is_modifying_own_profile' => false, // Pour détecter si l'admin modifie son propre profil
        ]);
    }
}
