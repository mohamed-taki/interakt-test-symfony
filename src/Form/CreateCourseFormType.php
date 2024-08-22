<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateCourseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Title must not be blank!',
                    ]),
                    new Length([
                        'min' => 2, 
                        'minMessage' => 'The title must be at least {{ limit }} characters long',
                    ]),
                ],
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'The value must be more than zero.'
                    ])
                ],
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'attr' => [
                        'class' => 'form-control'
                ],
                'constraints' => [
                    new File(
                        extensions:['png', 'jpeg', 'jpg', '.svg'],
                        maxSize: "4M",
                        maxSizeMessage: "File is too large !",
                        extensionsMessage: 'Invalid Image Format',
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
