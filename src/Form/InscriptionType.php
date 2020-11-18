<?php

namespace App\Form;

use App\Entity\Inscription;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->eleve =$options['eleve'];
        $eleve = $this->eleve;

        $builder

            //->add('annee')
            ->add('verse',IntegerType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Le montant versé", 'autocomplete'=>"off"]])
            //->add('restant')
            //->add('createdAt')
            //->add('createdBy')
            ->add('eleve', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=> 'App:Eleve',
                'query_builder'=> function(EntityRepository $er) use($eleve){
                    return $er->findEleve($eleve);
                }
            ])
            /*->add('classe', EntityType::class,[
                'attr'=>['class'=>'form-control select2'],
                'class'=>'App\Entity\Classe',
                'query_builder'=>function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label'=>'libelle',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
            'eleve' => null
        ]);
    }
}
