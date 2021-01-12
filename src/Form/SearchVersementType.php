<?php

namespace App\Form;

use App\Entity\Versement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchVersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('numero')
            //->add('annee')
            //->add('verse')
            //->add('restant')
            ->add('numero', TextType::class,[
                'attr'=>['class'=>'form-control date', 'placeholder'=>"Debut période", 'autocomplete'=>"off"],
                'required'=>false,
                'label' => "Date début"
            ])
            //->add('createdBy')
            ->add('date', TextType::class,[
                'attr'=>['class'=>'form-control date', 'placeholder'=>"Fin période", 'autocomplete'=>"off"],
                'required'=>false,
                'label' => "Date fin"
            ])
            //->add('eleve')
            ->add('classe', EntityType::class,[
                'attr'=>['class'=>'form-control js-select2'],
                'class'=>'App\Entity\Classe',
                'query_builder'=>function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label'=>'libelle',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
        ]);
    }
}
