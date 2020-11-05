<?php

namespace App\Form;

use App\Entity\Eleve;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('matricule')
            ->add('nom', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Nom de famille",'autocomplete'=>"off"]])
            ->add('prenoms', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Prenoms",'autocomplete'=>"off"]])
            ->add('dateNaissance', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off",'placeholder'=>"Date de naissance"]])
            ->add('lieuNaissance', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Lieu de naissance",'autocomplete'=>"off"]])
            ->add('sexe', ChoiceType::class,[
                'attr'=>['class'=>'form-control'],
                'choices'=>[
                    '-' => '',
                    'GARCON' => 'GARCON',
                    'FILLE' => 'FILLE'
                ]
            ])
            ->add('nationalite', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Nationalité",'autocomplete'=>"on"]])
            ->add('domicile', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Domicile",'autocomplete'=>"off"], 'required'=>false ])
            ->add('nomParent', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Nom et prenoms du parent",'autocomplete'=>"off"]])
            ->add('professionParent', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Profession du parent",'autocomplete'=>"off"], 'required'=>false])
            ->add('contactParent', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Contact du parent",'autocomplete'=>"off"]])
            ->add('residence', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Lieu de résidence du parent",'autocomplete'=>"off"]])
            ->add('nomTuteur', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Nom et prenoms du tuteur",'autocomplete'=>"off"]])
            ->add('professionTuteur', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Profession du tuteur",'autocomplete'=>"off"]])
            ->add('contactTuteur', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Contact du tuteur",'autocomplete'=>"off"]])
            ->add('residenceTuteur', TextType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"Lieu de residence",'autocomplete'=>"off"]])
            //->add('annee')
            ->add('classe', EntityType::class,[
                'attr'=>['class'=>'form-control js-select2'],
                'class'=>'App\Entity\Classe',
                'query_builder'=>function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label'=>'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
