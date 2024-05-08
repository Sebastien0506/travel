<?php

namespace App\Form;

use App\Entity\Avion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AvionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Type de l'avion",
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir le nom de l'avion",
                    ]),
                ],
            ])
            ->add('places', NumberType::class, [
                'label' => "Nombre de places",
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez indiquer le nombre de places",
                    ]),
                    new Range([
                        'min' => 1,
                        'max' => 500,
                        'minMessage' => "Le nombre de place doit être au moins de {{ limit }}.",
                        'maxMessage' => "Le nombre de places ne peut pas excéder {{ limit }}."
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avion::class,
        ]);
    }
}
