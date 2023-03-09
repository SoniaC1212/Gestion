<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    
                    ],
            ])
            ->add('niveau',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    
                    ],
            ])
            ->add('note',IntegerType::class,[
                'attr' => [
                'class' => 'form-control',
                
                ],
                ])
               
                
            ->add('image', FileType::class, [
                'label' => 'File',
                'attr' => ['class' => 'dropzone',
                'class' => 'form-control',
                
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier valide (jpeg, png).',
                    ]),
                ],
                ])
            
            ->add('Enregistrer', SubmitType::class,[
                'attr' => [
                    'class' => 'form-control',
                    
                    ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
