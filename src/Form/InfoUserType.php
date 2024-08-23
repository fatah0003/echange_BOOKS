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
            ])
            ->add('phoneNumber', IntegerType::class, [
                'label' => 'Numéro de Téléphone',
                'required' => true,
                'attr' => [
                    'class' => 'input-form'
                ],
                'label_attr' => [
                    'class' => 'label-form'
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
            ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfosUser::class,
        ]);
    }
}
