<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomProduit')
            ->add('PrixProduit')
            ->add('QuantiteProduit')
            ->add('ImageProduit')
            ->add('DescriptionProduit')
            ->add('categorie',EntityType::class,[
                'class'=>Categorie::class,
                'choice_label'=>'NomCategorie',
                'multiple'=>false,
                'expanded'=>false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
