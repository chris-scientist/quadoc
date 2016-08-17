<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\SearchController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DechetEquipementBundle\Entity\Contrat;
use DechetEquipementBundle\Entity\Equipement;
use DechetEquipementBundle\Entity\Intervention;
use DechetEquipementBundle\Form\EquipementType;
use DechetEquipementBundle\Form\ContratType;
use DechetEquipementBundle\Form\InterventionType;
use DechetEquipementBundle\Form\FiltreEquipementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EquipementController extends SearchController
{
    const CONST_OPTXT_LIKE = "like" ;
    const CONST_OPTXT_EQUALTO = "equal_to" ;
    const CONST_NOM = "nom" ;
    const CONST_EMPLACEMENT = "emplacement" ;
    const CONST_ACHETE_DEBUT = "achete_debut" ;
    const CONST_ACHETE_FIN = "achete_fin" ;
    const CONST_GARANTIE_DEBUT = "garantie_debut" ;
    const CONST_GARANTIE_FIN = "garantie_fin" ;
    const CONST_MES_DEBUT = "mse_debut" ;
    const CONST_MES_FIN = "mse_fin" ;
    const CONST_REFORME_DEBUT = "reforme_debut" ;
    const CONST_REFORME_FIN = "reforme_fin" ;
    
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @Route("/equipement/index", name="eqt_index")
     * @Security("has_role('ROLE_VISITEUR')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // [Début de la requête] Récupérer les équipements actifs (non réformés).
        $dql = 'SELECT e FROM DechetEquipementBundle:Equipement e ' ;
        $dql .= $this->addKeyword() . $this->addConstraintIsNull('e.reformeLe', true) ; // ................. Filtrer les équipements réformés.
        
        $form = $this->createForm(FiltreEquipementType::class) ; // ................ création du formulaire des filtres
        $form->handleRequest($request) ;
        
        $this->initCombinationFlag($form) ; // [à ne pas oublier] permet de combiner les contraintes entre elles, et/ou du formulaire.
        // Récupération des valeurs saisies par l'utilisateur.
        $nom = $form['nom']['searched_value']->getData() ;
        $nomFiltre = ( ! is_null($nom) ) ;
        $emplacement = $form['emplacement']['searched_value']->getData() ;
        $emplacementFiltre = ( ! is_null($emplacement) ) ;
        $categories = $form['categorie']->getData() ;
        $acheteBgn = $form['achete_le']['begin_date']->getData() ;
        $acheteEnd = $form['achete_le']['end_date']->getData() ;
        $acheteBgnFiltre = ( ! is_null($acheteBgn) ) ;
        $acheteEndFiltre = ( ! is_null($acheteEnd) ) ;
        $equipes = $form['equipe']->getData() ;
        $garantieBgn = $form['fingarantie_le']['begin_date']->getData() ;
        $garantieEnd = $form['fingarantie_le']['end_date']->getData() ;
        $garantieBgnFiltre = ( ! is_null($garantieBgn) ) ;
        $garantieEndFiltre = ( ! is_null($garantieEnd) ) ;
        $marques = $form['marque']->getData() ;
        $miseenserviceBgn = $form['miseenservice_le']['begin_date']->getData() ;
        $miseenserviceEnd = $form['miseenservice_le']['end_date']->getData() ;
        $miseenserviceBgnFiltre = ( ! is_null($miseenserviceBgn) ) ;
        $miseenserviceEndFiltre = ( ! is_null($miseenserviceEnd) ) ;
        $fournisseurs = $form['fournisseur']->getData() ;
        $reformeBgn = $form['reforme_le']['begin_date']->getData() ;
        $reformeEnd = $form['reforme_le']['end_date']->getData() ;
        $reformeBgnFiltre = ( ! is_null($reformeBgn) ) ;
        $reformeEndFiltre = ( ! is_null($reformeEnd) ) ;
        
        if($form->isValid()) // Lorsque le formulaire est validé.
        {
            if( $nomFiltre ) // [Recherche textuelle] Si un filtre existe sur le nom.
            {
                $opNom = $form['nom']['op_text']->getData() ; // Récupérer le type de recherche : égale à ou contient.
                if( $opNom == self::CONST_OPTXT_EQUALTO )
                {
                    $constraint = $this->addConstraintTextEqualTo('e.nom', self::CONST_NOM) ;
                }
                else if( $opNom == self::CONST_OPTXT_LIKE )
                {
                    $constraint = $this->addConstraintLike('e.nom', self::CONST_NOM) ;
                    $nom = $this->addLikeValue($nom) ;
                }
                $this->addConstraint( $constraint ) ; // Ajouter la contrainte à la requête.
            }
            
            if( $emplacementFiltre )
            {
                $opEmplacement = $form['emplacement']['op_text']->getData() ;
                if( $opEmplacement == self::CONST_OPTXT_EQUALTO )
                {
                    $constraint = $this->addConstraintTextEqualTo('e.emplacement', self::CONST_EMPLACEMENT) ;
                }
                else if( $opEmplacement == self::CONST_OPTXT_LIKE )
                {
                    $constraint = $this->addConstraintLike('e.emplacement', self::CONST_EMPLACEMENT) ;
                    $emplacement = $this->addLikeValue($emplacement) ;
                }
                $this->addConstraint( $constraint ) ;
            }
            
            if( count($categories) > 0 ) // [Recherche parmis une liste] Si un filtre existe sur les catégories.
            {
                $constraint = $this->addConstraintIn('e.categorie', $categories) ;
                $this->addConstraint($constraint) ;
            }
            
            if( $acheteBgnFiltre || $acheteEndFiltre ) // [Recherche temporelle] Si au moins une date d'achat est renseignée.
            {
                if( $acheteBgnFiltre && ! $acheteEndFiltre ) // Seule la date de début est renseignée, on recherche les équipements achetés "depuis" la date fournie.
                {
                    $constraint = $this->addConstraintFromDate('e.acheteLe', self::CONST_ACHETE_DEBUT) ;
                }
                else if( $acheteEndFiltre && ! $acheteBgnFiltre ) // Seule la date de fin est renseignée, on recherche les équipements achetés "jusqu'à" la date fournie.
                {
                    $constraint = $this->addConstraintToDate('e.acheteLe', self::CONST_ACHETE_FIN) ;
                }
                else // Deux dates sont renseignées, on recherche les équipements achetés "entre" ces deux dates.
                {
                    $constraint = $this->addConstraintDateBtw('e.acheteLe', self::CONST_ACHETE_DEBUT, self::CONST_ACHETE_FIN) ;
                }
                $this->addConstraint( $constraint ) ;
            }
            
            if( count($equipes) > 0 )
            {
                $constraint = $this->addConstraintIn('e.equipe', $equipes) ;
                $this->addConstraint($constraint) ;
            }
            
            if( $garantieBgnFiltre || $garantieEndFiltre )
            {
                if( $garantieBgnFiltre && ! $garantieEndFiltre )
                {
                    $constraint = $this->addConstraintFromDate('e.fingarantieLe', self::CONST_GARANTIE_DEBUT) ;
                }
                else if( $garantieEndFiltre && ! $garantieBgnFiltre )
                {
                    $constraint = $this->addConstraintToDate('e.fingarantieLe', self::CONST_GARANTIE_FIN) ;
                }
                else
                {
                    $constraint = $this->addConstraintDateBtw('e.fingarantieLe', self::CONST_GARANTIE_DEBUT, self::CONST_GARANTIE_FIN) ;
                }
                $this->addConstraint( $constraint ) ;
            }
            
            if( count($marques) > 0 )
            {
                $constraint = $this->addConstraintIn('e.marque', $marques) ;
                $this->addConstraint($constraint) ;
            }
            
            if( $miseenserviceBgnFiltre || $miseenserviceEndFiltre )
            {
                if( $miseenserviceBgnFiltre && ! $miseenserviceEndFiltre )
                {
                    $constraint = $this->addConstraintFromDate('e.miseenserviceLe', self::CONST_MES_DEBUT) ;
                }
                else if( $miseenserviceEndFiltre && ! $miseenserviceBgnFiltre )
                {
                    $constraint = $this->addConstraintToDate('e.miseenserviceLe', self::CONST_MES_FIN) ;
                }
                else
                {
                    $constraint = $this->addConstraintDateBtw('e.miseenserviceLe', self::CONST_MES_DEBUT, self::CONST_MES_FIN) ;
                }
                $this->addConstraint( $constraint ) ;
            }
            
            if( count($fournisseurs) > 0 )
            {
                $constraint = $this->addConstraintIn('e.fournisseur', $fournisseurs) ;
                $this->addConstraint($constraint) ;
            }
            
            if( $reformeBgnFiltre || $reformeEndFiltre )
            {
                if( $reformeBgnFiltre && ! $reformeEndFiltre )
                {
                    $constraint = $this->addConstraintFromDate('e.reformeLe', self::CONST_REFORME_DEBUT) ;
                }
                else if( $reformeEndFiltre && ! $reformeBgnFiltre )
                {
                    $constraint = $this->addConstraintToDate('e.reformeLe', self::CONST_REFORME_FIN) ;
                }
                else
                {
                    $constraint = $this->addConstraintDateBtw('e.reformeLe', self::CONST_REFORME_DEBUT, self::CONST_REFORME_FIN) ;
                }
                $this->addConstraint( $constraint ) ;
            }
            
            $dql .= $this->getConstraintsToText() ;
        }
        
        $query = $em->createQuery($dql) ;
        if( $nomFiltre ) {
            $query->setParameter(self::CONST_NOM, $nom) ;
        }
        if( $emplacementFiltre ) {
            $query->setParameter(self::CONST_EMPLACEMENT, $emplacement) ;
        }
        if( $acheteBgnFiltre || $acheteEndFiltre )
        {
            if( $acheteBgnFiltre && ! $acheteEndFiltre )
            {
                $query->setParameter(self::CONST_ACHETE_DEBUT, $acheteBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else if( $acheteEndFiltre && ! $acheteBgnFiltre )
            {
                $query->setParameter(self::CONST_ACHETE_FIN, $acheteEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else
            {
                $query->setParameter(self::CONST_ACHETE_DEBUT, $acheteBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
                $query->setParameter(self::CONST_ACHETE_FIN, $acheteEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
        }
        if( $garantieBgnFiltre || $garantieEndFiltre )
        {
            if( $garantieBgnFiltre && ! $garantieEndFiltre )
            {
                $query->setParameter(self::CONST_GARANTIE_DEBUT, $garantieBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else if( $garantieEndFiltre && ! $garantieBgnFiltre )
            {
                $query->setParameter(self::CONST_GARANTIE_FIN, $garantieEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else
            {
                $query->setParameter(self::CONST_GARANTIE_DEBUT, $garantieBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
                $query->setParameter(self::CONST_GARANTIE_FIN, $garantieEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
        }
        if( $miseenserviceBgnFiltre || $miseenserviceEndFiltre )
        {
            if( $miseenserviceBgnFiltre && ! $miseenserviceEndFiltre )
            {
                $query->setParameter(self::CONST_MES_DEBUT, $miseenserviceBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else if( $miseenserviceEndFiltre && ! $miseenserviceBgnFiltre )
            {
                $query->setParameter(self::CONST_MES_FIN, $miseenserviceEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else
            {
                $query->setParameter(self::CONST_MES_DEBUT, $miseenserviceBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
                $query->setParameter(self::CONST_MES_FIN, $miseenserviceEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
        }
        if( $reformeBgnFiltre || $reformeEndFiltre )
        {
            if( $reformeBgnFiltre && ! $reformeEndFiltre )
            {
                $query->setParameter(self::CONST_REFORME_DEBUT, $reformeBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else if( $reformeEndFiltre && ! $reformeBgnFiltre )
            {
                $query->setParameter(self::CONST_REFORME_FIN, $reformeEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
            else
            {
                $query->setParameter(self::CONST_REFORME_DEBUT, $reformeBgn, \Doctrine\DBAL\Types\Type::DATETIME) ;
                $query->setParameter(self::CONST_REFORME_FIN, $reformeEnd, \Doctrine\DBAL\Types\Type::DATETIME) ;
            }
        }
        
        $equipements = $query->getResult() ;
        
        $export = $form['toexport']->getData() ;
        
        if( $export == "on" ) // Exporter les données filtrées.
        {
            $path = __DIR__ . '/../../../web/download/' ;
            $filename = time() . '.csv' ;
            $csvFile = new \Keboola\Csv\CsvFile($path . $filename, ";", "") ;
            $csvFile->writeRow( Equipement::getLabels() ) ;
            foreach ( $equipements as $equipement ) {
                $csvFile->writeRow( $equipement->toArray() ) ;
            }
            
            $response = new Response() ;
            $response->setContent(file_get_contents($path . $filename)) ;
            $response->headers->set('Content-Type', 'application/force-download') ;
            $response->headers->set('Content-disposition', 'filename=' . $filename) ;

            return $response ;
        }
        
        return $this->render('equipement/index.html.twig', array(
            'equipements' => $equipements,
            'form' => $form->createView()
        ) ) ;
    }
    /**
     * @Route("/intervention/index", name="eqt_show_intervention")
     * @Security("has_role('ROLE_VISITEUR')")
     */
    public function showInterventionAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Récupérer les interventions pour les équipements actifs (non réformés).
        $dql = "SELECT i FROM DechetEquipementBundle:Intervention i " .
               "LEFT JOIN i.equipement e " .
               "WHERE e.reformeLe IS NULL" ;
        $interventions = $em->createQuery($dql)->getResult() ;
        
        return $this->render('equipement/show_intervention.html.twig', array(
            'interventions' => $interventions
        ) ) ;
    }
    /**
     * @Route("/equipement/contrat/", name="eqt_show_contrat")
     * @Security("has_role('ROLE_VISITEUR')")
     */
    public function showContratAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Récupérer les contrats pour les équipements actifs (non réformés)
        // et seulement pour les équipements (pas les "contrats déchets").
        $dql = "SELECT c FROM DechetEquipementBundle:Contrat c " .
               "LEFT JOIN c.equipements e " .
               "WHERE e.reformeLe IS NULL " .
               "AND c.contratdechet = FALSE" ;
        $contrats = $em->createQuery($dql)->getResult() ;
        
        return $this->render('equipement/show_contrat.html.twig', array(
            'contrats' => $contrats
        ) ) ;
    }
    /**
     * @Route("/equipement/add", name="eqt_add")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE') or has_role('ROLE_ANIM_PREVENTION') or has_role('ROLE_ANIM_CHARTESANITAIRE') or has_role('ROLE_ANIM_SME') or has_role('ROLE_RESPONSABLE') or has_role('ROLE_UTILISATEUR')")
     */
    public function addAction(Request $request)
    {
        $equipement = new Equipement() ;
        $form = $this->createForm(EquipementType::class, $equipement) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            if( ! is_null($equipement->getFichier()) )
            {
                $equipement->setBFichier(true) ;
            }
            if( ! is_null($equipement->getPhoto()) )
            {
                $equipement->setBPhoto(true) ;
            }
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($equipement) ;
            $em->flush() ;
            
            return $this->redirectToRoute("eqt_index") ;
        }
        
        return $this->render('equipement/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/equipement/contrat/{id}",
     *  name="eqt_contrat",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE') or has_role('ROLE_ANIM_PREVENTION') or has_role('ROLE_ANIM_CHARTESANITAIRE') or has_role('ROLE_ANIM_SME') or has_role('ROLE_RESPONSABLE') or has_role('ROLE_UTILISATEUR')")
     */
    public function addContratAction(Equipement $eqt, Request $request)
    {
        $contrat = new Contrat() ;
        $contrat->setContratdechet(false) ;
        $eqt->addContratequipement($contrat) ;
        $form = $this->createForm(ContratType::class, $contrat) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($contrat) ;
            $em->flush() ;
            
            return $this->redirectToRoute("eqt_index") ;
        }
        
        return $this->render('equipement/add_contrat.html.twig',
                array(
                    'eqt' => $eqt,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/equipement/intervention/{id}",
     *  name="eqt_intervention",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE') or has_role('ROLE_ANIM_PREVENTION') or has_role('ROLE_ANIM_CHARTESANITAIRE') or has_role('ROLE_ANIM_SME') or has_role('ROLE_RESPONSABLE') or has_role('ROLE_UTILISATEUR')")
     */
    public function addInterventionAction(Equipement $eqt, Request $request)
    {
        $intervention = new Intervention() ;
        $eqt->addHistorique($intervention) ;
        $form = $this->createForm(InterventionType::class, $intervention) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            if( ! is_null($intervention->getFichier()) )
            {
                $intervention->setBFichier(true) ;
            }
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($intervention) ;
            $em->flush() ;
            
            return $this->redirectToRoute("eqt_index") ;
        }
        
        return $this->render('equipement/add_intervention.html.twig',
                array(
                    'eqt' => $eqt,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/equipement/remove/{id}",
     *  name="eqt_remove",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE') or has_role('ROLE_ANIM_PREVENTION')")
     */
    public function remove(Equipement $eqt, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($eqt) ;
        $contrats = $eqt->getContratequipement() ;
        foreach ( $contrats as $cnt )
        {
            $em->remove($cnt) ;
        }
        $interventions = $eqt->getHistorique() ;
        foreach( $interventions as $int ) 
        {
            $em->remove($int) ;
        }
        $em->flush() ;
        
        return $this->redirectToRoute("eqt_index") ;
    }
    /**
     * @Route(
     *  "/intervention/download/{id}",
     *  name="eqt_dl_intervention",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_VISITEUR')")
     */
    public function downloadInterventionAction(Intervention $int, Request $request)
    {
        $path = $int->getUploadDir() ;
        $filename = $int->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * @Route(
     *  "/equipement/download/{id}",
     *  name="eqt_dl_equipement",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_VISITEUR')")
     */
    public function downloadEquipementAction(Equipement $eqt, Request $request)
    {
        $path = $eqt->getUploadFichierDir() ;
        $filename = $eqt->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * Récupérer les informations relatives à un équipement.
     * @param Equipement $eqt
     * @param Request $request
     * 
     * @Route(
     *  "/equipement/info/{id}",
     *  name="eqt_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_VISITEUR')")
     * @Method({"GET"})
     */
    public function infoAction(Equipement $eqt, Request $request)
    {
        $n_immo = $eqt->getNImmobilisation() ;
        $reforme = $eqt->getReformeLe() ;
        
        $contratsArray = array() ;
        $contrats = $eqt->getContratequipement() ;
        foreach( $contrats as $cnt )
        {
            $numero = $cnt->getNumero() ;
            $cout = $cnt->getCout() ;
            $commentaire = $cnt->getCommentaire() ;
            $contratArray = array() ;
            $contratArray[ "id" ] = $cnt->getId() ;
            $contratArray[ "debut" ] = $cnt->getDebut()->format('d/m/Y') ;
            $contratArray[ "fin" ] = $cnt->getFin()->format('d/m/Y') ;
            $contratArray[ "numero" ] = ( is_null($numero) ? "" : $numero ) ;
            $contratArray[ "cout" ] = ( is_null($cout) ? "" : $cout ) ;
            $contratArray[ "commentaire" ] = ( is_null($commentaire) ? "" : $commentaire ) ;
            $contratsArray[] = $contratArray ;
        }
        
        $interventionsArray = array() ;
        $interventions = $eqt->getHistorique() ;
        foreach( $interventions as $int )
        {
            $operateur = $int->getOperateur() ;
            $commentaire = $int->getCommentaire() ;
            $bFichier = $int->getBFichier() ;
            $interventionArray = array() ;
            $interventionArray[ "id" ] = $int->getId() ;
            $interventionArray[ "operation" ] = $int->getOperation() ;
            $interventionArray[ "operateur" ] = $operateur->getNom() . ' ' . $operateur->getPrenom() ;
            $interventionArray[ "afaire" ] = $int->getAfaireLe()->format('d/m/Y') ;
            $interventionArray[ "effectue" ] = $int->getEffectueLe()->format('d/m/Y') ;
            $interventionArray[ "commentaire" ] = ( is_null($commentaire) ? "" : $commentaire ) ;
            $interventionArray[ "bfichier" ] = $bFichier ;
            $interventionsArray[] = $interventionArray ;
        }
        
        $caracteristiques = $eqt->getCaracteristiques() ;
        $equipementArray = array() ;
        $equipementArray[ "id" ] = $eqt->getId() ;
        $equipementArray[ "nom" ] = $eqt->getNom() ;
        $equipementArray[ "categorie" ] = $eqt->getCategorie()->getNom() ;
        $equipementArray[ "nserie" ] = $eqt->getNSerie() ;
        if( ! is_null($n_immo) )
        {
            $equipementArray[ "nimmo" ] = $n_immo ;
        }
        $equipementArray[ "model" ] = $eqt->getModele() ;
        $equipementArray[ "emp" ] = $eqt->getEmplacement() ;
        $equipementArray[ "fou" ] = $eqt->getFournisseur()->getNom() ;
        $equipementArray[ "mar" ] = $eqt->getMarque()->getNom() ;
        $equipementArray[ "res" ] = $eqt->getResponsable()->getNom() . ' ' . $eqt->getResponsable()->getPrenom() ;
        $equipementArray[ "equipe" ] = $eqt->getEquipe()->getNom() ;
        $equipementArray[ "caracteristiques" ] = ( is_null($caracteristiques) ? "" : $caracteristiques ) ;
        $equipementArray[ "achat" ] = $eqt->getAcheteLe()->format('d/m/Y') ;
        $equipementArray[ "garantie" ] = $eqt->getFingarantieLe()->format('d/m/Y') ;
        $equipementArray[ "miseenservice" ] = $eqt->getMiseenserviceLe()->format('d/m/Y') ;
        if( ! is_null($reforme) )
        {
            $equipementArray[ "reforme" ] = $reforme->format('d/m/Y') ;
        }
        $equipementArray[ "bfichier" ] = $eqt->getBFichier() ;
        $equipementArray[ "contrats" ] = $contratsArray ;
        $equipementArray[ "interventions" ] = $interventionsArray ;
        
        $response = new Response(json_encode($equipementArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
    /**
     * @Route(
     *  "/equipement/reformer/{id}",
     *  name="eqt_reformer",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE')")
     */
    public function archiverAction(Equipement $eqt, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        
        $eqt->setReformeLe( new \DateTime() ) ;
        $em->flush() ;
        
        return $this->redirectToRoute("archives_eqt") ;
    }
    /**
     * @Route("/archives/equipement", name="archives_eqt")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE')")
     */
    public function archiveAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Requête DQL pour filtrer les équipements qui sont réformés.
        $dql = "SELECT e FROM DechetEquipementBundle:Equipement e " .
               "WHERE e.reformeLe IS NOT NULL" ;
        $equipements = $em->createQuery($dql)->getResult() ;
        
        return $this->render('equipement/index.html.twig', array(
            'equipements' => $equipements
        ) ) ;
    }
}
