<?php


namespace App\Utilities;


use App\Entity\Scolarite;
use App\Repository\EleveRepository;
use App\Repository\InscriptionRepository;
use App\Repository\ScolariteRepository;
use Cassandra\Date;
use Doctrine\ORM\EntityManagerInterface;

class GestionEleve
{
    private $eleveRepository;
    private $inscriptionRepository;
    private $em;
    private $scolariteRepository;

    public function __construct(EleveRepository $eleveRepository, InscriptionRepository $inscriptionRepository, EntityManagerInterface $entityManager, ScolariteRepository $scolariteRepository)
    {
        $this->eleveRepository = $eleveRepository;
        $this->inscriptionRepository = $inscriptionRepository;
        $this->scolariteRepository = $scolariteRepository;
        $this->em = $entityManager;
    }

    /**
     * @return string
     */
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

    /**
     * Generation du recu
     * @return string
     */
    public function recuInscription():string
    {
        $inscription = $this->inscriptionRepository->findOneBy([],['id'=>"DESC"]);
        if (!$inscription){
            $id = 1;
        }else{
            $id = $inscription->getId() + 1;
        }

        $recu = $this->recu($id);

        return $recu;
    }

    /**
     * @param null $inscriptionID
     * @param null $versementID
     * @return bool
     */
    public function scolarite($inscriptionID = null, $versementID = null)
    {
        if ($inscriptionID){
            $inscription = $this->inscriptionRepository->findOneBy(['id'=>$inscriptionID]);
            if (!$inscription){
                return false;
            }
            $total = $inscription->getVerse() + $inscription->getRestant();
            // Insertion dans la table scolarite
            $scolarite = new Scolarite();
            $scolarite->setEleve($inscription->getEleve());
            $scolarite->setAnnee($inscription->getAnnee());
            $scolarite->setTotal($total);
            $scolarite->setVerse($inscription->getVerse());
            $scolarite->setRestant($inscription->getRestant());
            if ($inscription->getRestant() === 0) $scolarite->setStatut(true);
            //else $scolarite->setStatut(false);
            $this->em->persist($scolarite);

            // MAJ de la table eleve
            $eleve = $inscription->getEleve();
            $eleve->setAnnee($inscription->getAnnee());

            $this->em->flush();

            return true;
        }

        return false;
    }

    /**
     * Mise a jour de la scolarite
     *
     * @param $eleveID
     * @param $annee
     * @param $total
     * @param $verse
     * @param $restant
     * @return bool
     */
    public function scolariteUpdated($eleveID, $annee, $total, $verse, $restant)
    {
        $eleve = $this->eleveRepository->findOneBy(['id'=>$eleveID]);
        $scolarite = $this->scolariteRepository->findOneBy(['eleve'=>$eleveID, 'annee'=>$annee]);
        if (!$scolarite) return false;
        $scolarite->setEleve($eleve);
        $scolarite->setAnnee($annee);
        $scolarite->setTotal($total);
        $scolarite->setVerse($verse);
        $scolarite->setRestant($restant);
        $this->em->flush();

        return true;
    }

    /**
     * @param $eleveID
     * @param $annee
     * @return bool
     */
    public function scolariteDeleted($eleveID, $annee)
    {
        //$eleve = $this->eleveRepository->findOneBy(['id'=>$eleveID]);
        $scolarite = $this->scolariteRepository->findOneBy(['eleve'=>$eleveID, 'annee'=>$annee]);
        if (!$scolarite) return false;
        $this->em->remove($scolarite);
        $this->em->flush();
        return true;
    }

    /**
     * @param $eleve
     * @return mixed
     */
    public function bordereau($eleve)
    {
        // Initialisation des compteurs
        $i = 0; $bordereaux = [];
        $inscription = $this->inscriptionRepository->findOneBy(['eleve'=>$eleve, 'annee'=>$this->annee()]);
        if ($inscription){
            $bordereaux[0] =[
                'date' => $inscription->getCreatedAt(),
                'recu' => $inscription->getNumero(),
                'verse' => $inscription->getVerse(),
                'reste' => $inscription->getRestant(),
                'type' => 'inscription',
                'id' => $inscription->getId(),
            ];
            $i = 1;
        }


        return $bordereaux;
    }

    /**
     * Formattage du recu
     *
     * @param $id
     * @return string
     */
    protected function recu($id): string
    {
        switch (strlen($id))
        {
            case 1:
                $code = '00000'.$id;
                break;
            case 2:
                $code = '0000'.$id;
                break;
            case 3:
                $code = '000'.$id;
                break;
            case 4:
                $code = '00'.$id;
                break;
            case 5:
                $code = '0'.$id;
                break;
            default:
                $code = $id;
        }

        return $code;
    }

}