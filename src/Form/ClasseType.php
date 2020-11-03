<?php

namespace App\Form;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"La classe", 'autocomplete'=>"off"],
                'label' => "La classe"
            ])
            ->add('scolarite', IntegerType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"le montant de la scolarité","autocomplete"=>"off"],
                'label' => "Scolarité"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
