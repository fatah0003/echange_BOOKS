<?php

namespace App\Form;

use App\Entity\BookCategorie;
use App\Entity\Books;
use App\Enum\ExchangeTypeEnum;
use App\Enum\StateEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints as Assert;

class ListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'title-input',
                    'class' => 'input-form'
                ],
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'author-input',
                    'class' => 'input-form'
                ],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN ou EAN',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'isbn-input',
                    'class' => 'input-form',
                    'placeholder' => 'Saisissez l\'ISBN ou l\'EAN sans les tirets'
                ],
                'constraints' => [
                new Assert\Regex([
                'pattern' => '/^\d+$/',
                'message' => 'L\'ISBN ou l\'EAN doit contenir uniquement des chiffres.'
                ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'text-form'
                ],
            ])
            ->add('edition', TextType::class, [
                'label' => 'Année d\'édition',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'edition-input',
                    'class' => 'input-form'
                ],
            ])
            ->add('location', TextType::class, [
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
            ->add('pages', IntegerType::class, [
                'label' => 'Nombre de pages',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'pages-input',
                    'class' => 'input-form'
                ],
            ])
            ->add('state', EnumType::class, [
                'class' => StateEnum::class,
                'label' => 'État du Livre',
                'required' => true,
                'label_attr' => [
                    'class' => 'required-label',
                ],
                'attr' => [
                    'class' => 'input-form',
                ],
                'choice_label' => function (StateEnum $state) {
                    return 'state.' . $state->value;
                },
            ])
            ->add('exchangeType', EnumType::class, [
                'class' => ExchangeTypeEnum::class,
                'multiple' => true,
                'label' => 'Type d\'échange',
                'expanded' => true,
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form-type'
                ],
                'choice_label' => function (ExchangeTypeEnum $type) {
                    return 'exchange_type.' . $type->value;
                },
            ])
            ->add('bookCategorie', EntityType::class, [
                'class' => BookCategorie::class,
                'choice_label' => 'name',
                'label' => 'Categorie',
                'required' => true,
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'data-action' => 'category-input',
                    'class' => 'input-form'
                ],
            ])
            ->add('back', ImageType::class, [
                'label' => 'Image Back',
                'required' => !$options['data']?->getBack(),
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form'
                ],
            ])
            ->add('cover', ImageType::class, [
                'label' => 'Image cover',
                'required' => !$options['data']?->getCover(),
                'label_attr' => [
                    'class' => 'label-form',
                ],
                'attr' => [
                    'class' => 'input-form'
                ],

            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);
    }
}
