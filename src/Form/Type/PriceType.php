<?php

namespace App\Form\Type;

use App\Form\DataTransformer\CentimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['divide'] === false) {
            return;
        }
        $builder->addModelTransformer(new CentimeTransformer);
        // On appelle la fonction qu'on a créé dans le dossier "DataTransformer" et toutes les options des fonctions ci-dessous !
    }

    public function getParent()
    {
        return NumberType::class;
        //Permet de récupérer tous les champs attribué de base à NumerType (sans la fonction diviser le prix)
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'divide' => true
        ]);

        // Permet d'activer cette fonction en plus par défaut
    }
}
