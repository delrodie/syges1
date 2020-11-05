<?php


namespace App\Utilities;


use App\Repository\EleveRepository;
use Cassandra\Date;

class GestionEleve
{
    private $eleveRepository;

    public function __construct(EleveRepository $eleveRepository)
    {
        $this->eleveRepository = $eleveRepository;
    }

    public function matricule()
    {
        $verification = false;
        //$matricule = 'test';

        while ($verification === false){
            // Année
            $mois = Date('m', time());
            if ($mois > 7) $debut = Date('y', time()) + 1;
            else $debut = Date('y', time()) - 1;

            // Code aleatoire
            $code = mt_rand(10000,99999);

            // Lettre aleatoire
            $alphabet="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $lettre = $alphabet[rand(0,25)];

            $matricule = $debut.''.$code.''.$lettre;

            $eleve = $this->eleveRepository->findOneBy(['matricule'=>$matricule]);
            if ($eleve) $verification = false;
            else $verification = true;
        }

        return $matricule;
    }

    /**
     * Annee acedmique
     *
     * @return string
     */
    public function annee():string
    {
        $mois_encours = Date('m', time());
        if ($mois_encours > 7){
            $debut_annee = Date('Y', time());
            $fin_annee = Date('Y', time())+1;
            //$an = Date('y', time())+1;
        }else{
            $debut_annee = Date('Y', time())-1;
            $fin_annee = Date('Y', time());
            //$an = Date('y', time());
        }

        $annee = $debut_annee.'-'.$fin_annee;

        return $annee;
    }

}