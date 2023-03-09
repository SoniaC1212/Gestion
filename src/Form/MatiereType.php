<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;



class MatiereType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    
                    ],
            ])
            ->add('cours',EntityType::class,[
                'label' => 'cours',
                'class' => Cours::class,
                
                'choice_label' => 'libelle',
                'multiple' => true,
                //'expanded' => true,
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
            'data_class' => Matiere::class,
        ]);
    }
}
