<?php

namespace App\Form;

use App\Entity\Versement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->eleve =$options['eleve'];
        $eleve = $this->eleve;

        $builder
            //->add('numero')
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
            //->add('classe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
            'eleve' => null
        ]);
    }
}
