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

class EquipementController extends SearchController
{
    const CONST_NOM = "nom" ;
    const CONST_OP_NOM = "operateur_nom" ;
    const CONST_GARANTIE_DEBUT = "garantie_debut" ;
    const CONST_GARANTIE_FIN = "garantie_fin" ;
    const CONST_OPTXT_LIKE = "like" ;
    const CONST_OPTXT_EQUALTO = "equal_to" ;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->addNamesUrlParameters(self::CONST_NOM, self::CONST_NOM) ;
        $this->addNamesUrlParameters(self::CONST_OP_NOM, self::CONST_OP_NOM) ;
        $this->addNamesUrlParameters(self::CONST_GARANTIE_DEBUT, self::CONST_GARANTIE_DEBUT) ;
        $this->addNamesUrlParameters(self::CONST_GARANTIE_FIN, self::CONST_GARANTIE_FIN) ;
    }
    /**
     * @Route("/equipement/index", name="eqt_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repEqt = $em->getRepository("DechetEquipementBundle:Equipement") ;
        
        // Récupérer les équipements actifs (non réformés).
        $dql = 'SELECT e FROM DechetEquipementBundle:Equipement e ' ;
        $dql .= $this->addKeyword() . $this->addConstraintIsNull('e.reformeLe', true, true) ; // ................. Filtrer les équipements réformés.
        
        $form = $this->createForm(FiltreEquipementType::class) ;
        $form->handleRequest($request) ;
        
        $nom = $form['nom']['searched_value']->getData() ;
        $nomFiltre = ( ! is_null($nom) ) ;
        
        if($form->isValid())
        {
            if( $nomFiltre )
            {
                $opNom =  $form['nom']['op_text']->getData() ;
                if( $opNom == self::CONST_OPTXT_EQUALTO )
                {
                    $constraint = $this->addConstraintTextEqualTo('e.nom', self::CONST_NOM) ;
                }
                else if( $opNom == self::CONST_OPTXT_LIKE )
                {
                    $constraint = $this->addConstraintLike('e.nom', self::CONST_NOM) ;
                    $nom = $this->addLikeValue($nom) ;
                }
                $this->addConstraint( $constraint ) ;
            }
            $dql .= $this->getConstraintsToText() ;
        }
        
        $query = $em->createQuery($dql) ;
        if( $nomFiltre ) {
            $query->setParameter(self::CONST_NOM, $nom) ;
        }
        $equipements = $query->getResult() ;
        
        return $this->render('equipement/index.html.twig', array(
            'equipements' => $equipements,
            'form' => $form->createView()
        ) ) ;
    }
    
    protected function buildAdvancedQuery(Request $request, $form, $dql)
    {
//            $val = $form['nom']['searched_value']->getData() ;
//            $val = $form['nom']['op_text']->getData() ;
//            $val = $form['achete_le']['begin_date']->getData() ;
//            dump($val) ;
        $nom = $form['nom']['searched_value']->getData() ;
        if( ! is_null($nom) )
        {
            $opNom =  $form['nom']['op_text']->getData() ;
            if( $opNom == self::CONST_OPTXT_EQUALTO )
            {
                $constraint = $this->addConstraintTextEqualTo(self::CONST_NOM, self::CONST_NOM) ;
            }
            else if( $opNom == self::CONST_OPTXT_LIKE )
            {
                $constraint = $this->addConstraintLike(self::CONST_NOM, self::CONST_NOM) ;
            }
            $this->addConstraint( $constraint ) ;
            $dql->setParameter(self::CONST_NOM, $nom) ;
        }
    }


    protected function searchQuery(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $this->initUrlParameters($request) ;
        // ..................................................................... Construire et exécuter la requête.
        $urlParameters = $this->getUrlParameters() ;
        $dql = 'SELECT e FROM DechetEquipementBundle:Equipement e ' ;
        $dql .= $this->addConstraintIsNull('e.reformeLe') ; // ................. Filtrer les équipements réformés.
        if( $this->parametersExists(self::CONST_NOM) )
        {
            $nom = $urlParameters[ self::CONST_NOM ] ;
            $operateurNom = $urlParameters[ self::CONST_OP_NOM ] ;
            if( $operateurNom == "like" )
            {
                $dql .= $this->addConstraintLike('e.nom', 'nom') ; // .......... Filtrer les noms ressemblant à une valeur donnée.
                $this->addQueryParameter('nom', $this->addLikeValue($nom)) ;
            }
            else if( $operateurNom == "equal" )
            {
                $dql .= $this->addConstraintTextEqualTo('e.nom', 'nom') ; // ... Filtrer les noms équivalent à une valeur donnée.
                $this->addQueryParameter('nom', $nom) ;
            }
        }
        if( $this->parametersExists(self::CONST_GARANTIE_DEBUT) || $this->parametersExists(self::CONST_GARANTIE_FIN) )
        {
            $dql .= $this->addConstraintDateBtw("e.fingarantieLe", "garantie_debut", "garantie_fin") ;
            $this->addQueryParameterDate("garantie_debut", $urlParameters[ self::CONST_GARANTIE_DEBUT ]) ;
            $this->addQueryParameterDate("garantie_fin", $urlParameters[ self::CONST_GARANTIE_FIN ]) ;
        }
        $query = $em->createQuery($dql) ;
        $this->addQueryParameters($query) ;
        return $query ;
    }
    /**
     * @Route("/intervention/export", name="eqt_export")
     */
    public function exportAction(Request $request)
    {
//        $query = $this->query($request) ;
////        $equipements = $query->getResult() ;
//        dump($query instanceof \Doctrine\ORM\QueryBuilder) ;
//        dump($query instanceof \Doctrine\DBAL\Query\QueryBuilder) ;
//        dump($query instanceof \Doctrine\ORM\Query) ;
//        $delimiter = ";" ;
//        $itResult = $query->getQuery()->iterate() ;
//        $handle = fopen('php://memory', 'r+') ;
//        $header = array() ;
//        
//        while( false !== ($row = $itResult->next()) )
//        {
//            fputcsv($handle, $row[0], $delimiter) ;
//            dump($row) ;
//        }
//        rewind($handle) ;
//        $content = stream_get_contents($handle) ;
//        fclose($handle) ;
//        
//        return new Response($content, 200, array(
//            'Content-Type' => 'application/force-download',
//            'Content-Disposition' => 'attachment; filename="export.csv"'
//        )) ;
        
        
        // ..................................................................... Récupérer les paramètres (filtres) envoyés dans l'URL
        $nom = $request->query->get('nom') ;
        $operateurNom = $request->query->get('operator_nom') ;
        $garantieDebut = $request->query->get('garantie_debut') ;
        $garantieFin = $request->query->get('garantie_fin') ;
        
        $urlParameters = array() ;
        if( $nom != "" ) {
            $urlParameters[ "nom" ] = $nom ;
        }
        
        $query = $this->searchQuery($request) ;
        $equipements = $query->getResult() ;
//        dump($equipements) ;
        return $this->redirectToRoute("eqt_search", $urlParameters) ;
    }
    
    /**
     * @Route("/intervention/search", name="eqt_search")
     */
    public function searchAction(Request $request)
    {
        $query = $this->searchQuery($request) ;
        $equipements = $query->getResult() ;
        
        return $this->render('equipement/index.html.twig', array(
            'equipements' => $equipements
        ) ) ;
    }
    /**
     * @Route("/intervention/index", name="eqt_show_intervention")
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
