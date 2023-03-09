<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Document;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormTypeInterface;



class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nomdocument',TextType::class, array(
                'label'=> 'nomdocument','required'=>true , 'attr' => array('maxlength' => 100, 'class' => 'libelle_text') ) )
            ->add('type',TextType::class, array(
                'label'=> 'type','required'=>true , 'attr' => array('maxlength' => 100, 'class' => 'libelle_text') ) )
            ->add('editeur',TextType::class, array(
                'label'=> 'editeur','required'=>true , 'attr' => array('maxlength' => 100, 'class' => 'libelle_text') ) )


;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
