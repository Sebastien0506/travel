<?php

namespace App\Form;

use App\Entity\Avion;
use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('nomDeLaDestination', TextType::class, [
                'label' => 'Nom de la destination',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir le nom de la destination",
                    ]),
                ],
           ])
           ->add('avions', EntityType::class, [
            'label' => "Veuillez sélectionner le ou les avion pour cette destination",
            'class' => Avion::class,
            'choice_label' => "nom",
            'multiple' => true,
            'expanded' => true,
           ])
           ->add('images', FileType::class, [
            'label' => "Image",
            'multiple' => true,
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new All([
                    'constraints' => [
                        new File([
                            'maxSize' => "1024K",
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger un fichier valide (JPEG, PNG, JPG)',
                        ]),
                    ],
                ]),
            ],
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
}
