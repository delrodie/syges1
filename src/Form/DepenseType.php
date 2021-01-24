<?php

namespace App\Form;

use App\Entity\Depense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', TextType::class,[
                'attr'=>['class'=>'form-control date', 'placeholder'=>"La date de depense", 'autocomplete'=>"off"],
                'label' => "Date de la dépense"
            ])
            ->add('description', TextareaType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"La description de la dépense", 'rows'=>5],
                'label' => "Description"
            ])
            ->add('montant', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Le montant de la dépense", 'autocomplete'=>"off"],
                'label' => "Montant"
            ])
            //->add('createdAt')
            //->add('annee')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depense::class,
        ]);
    }
}
