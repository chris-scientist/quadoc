<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class SearchController extends Controller
{
    const CONST_OP_CONTRAINTE = "operateur_contrainte" ;
    //
    // true si la clause "where" a été ajouté à la requête, 
    // et false si la clause "where" n'a pas été ajouté (par défaut).
    private $whereFlag ;
    //
    // true si les contraintes sont liées par un "et logique" (par défaut),
    // et false si les contraintes sont liées par un "ou logique".
    private $andCombinationFlag ;
    //
    // Paramètres de la requête DQL (sauf les dates).
    private $queryParameters ;
    //
    // Paramètres de type date de la requête DQL.
    private $queryParametersDate ;
    //
    // Paramètres passés dans l'URL.
    private $urlParameters ;
    //
    // Noms des paramètres passés dans l'URL.
    private $namesUrlParameters ;
    //
    // Tableau composé d'un groupe de contrainte.
    private $constraints ;
    
    public function __construct()
    {
        $this->whereFlag = false ;
        $this->andCombinationFlag = true ;
        
        $this->queryParameters = array() ;
        $this->queryParametersDate = array() ;
        $this->urlParameters = array() ;
        $this->namesUrlParameters = array() ;
        $this->constraints = array() ;
        
        $this->addNamesUrlParameters(self::CONST_OP_CONTRAINTE, self::CONST_OP_CONTRAINTE) ;
    }
    // 
    // Construire la requête (DQL) pour filtrer les données.
    abstract protected function searchQuery(Request $request) ;
    //
    // Construire la requête (DQL) avancée pour filtrer les données.
    abstract protected function buildAdvancedQuery(Request $request, $form, $dql) ;
    //
    // *************************************************************************
    //
    // Gestion des contraintes.
    //
    // *************************************************************************
    //
    // Ajouter une contrainte.
    protected function addConstraint($aConstraint)
    {
        $this->constraints[] = $aConstraint ;
    }
    //
    // Récupérer les contraintes sous la forme d'une chaîne de caractères.
    protected function getConstraintsToText()
    {
        $constraints = 'AND (' ;
        
//        foreach( $this->constraints as $constraint ) {
        for( $itC = 0 ; $itC < count($this->constraints) ; $itC++ ) {
//            $constraints .= $constraint ;
            $constraint = $this->constraints[$itC] ;
            if( $itC > 0 ) {
                $constraints .= $this->addKeyword() ;
            }
            $constraints .= $constraint ;
        }
        $constraints .= ')';
        
        if( count($this->constraints) == 0 ) {
            $constraints = '' ;
        }
        
        return $constraints ;
    }
    //
    // *************************************************************************
    //
    // Construction de la clause "where".
    //
    // *************************************************************************
    // 
    // -------------------------------------------------------------------------
    // Contraintes relatives au attribut pouvant valoir Null.
    // -------------------------------------------------------------------------
    // 
    // Ajouter une contrainte "is [not] null" sur un champ.
    protected function addConstraintIsNull($aField, $isNull = true, $forceAndKeyword = false)
    {
        $constraint = $aField . ' IS ' ;
        if( ! $isNull ) {
            $constraint .= 'NOT ' ;
        }
        $constraint .= 'NULL ' ;
        return $constraint ;
    }
    // 
    // -------------------------------------------------------------------------
    // Contraintes relatives au zone de saisie libre, autrement dit au texte.
    // -------------------------------------------------------------------------
    //
    // Ajouter une contrainte "like" sur un champ.
    protected function addConstraintLike($aField, $aParamValue, $forceAndKeyword = false)
    {
        $constraint =   'UPPER(' . $aField . ') ' .
                        'LIKE ' .
                        'UPPER(:' . $aParamValue . ') ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "=" (égale à) sur un champ.
    protected function addConstraintTextEqualTo($aField, $aParamValue, $forceAndKeyword = false)
    {
        $constraint =   'UPPER(' . $aField . ') ' .
                        '= ' .
                        'UPPER(:' . $aParamValue . ') ' ;
        return $constraint ;
    }
    // 
    // -------------------------------------------------------------------------
    // Contraintes relatives au date.
    // -------------------------------------------------------------------------
    //
    // Ajouter une contrainte "entre" sur un champ date.
    protected function addConstraintDateBtw($aField, $aParamBegin, $aParamEnd, $forceAndKeyword = false)
    {
        $constraint = $this->addKeyword($forceAndKeyword) . $aField .
                        ' BETWEEN :' . $aParamBegin .
                        ' AND :' . $aParamEnd . ' ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "jusqu'à" une date (aParamValue).
//    protected function addConstraintDate($aField, $aParamValue)
//    {
//        $constraint = $this->addConstraint() . $aField .
//                        ' ';
//                        
//     http://stackoverflow.com/questions/10681158/how-to-compare-datetime-field-from-doctrine2-with-a-date
//                        
//        return ;
//    }
    // 
    // -------------------------------------------------------------------------
    // Méthodes complémentaires.
    // -------------------------------------------------------------------------
    //
    // Encardrer la valeur par "%".
    protected function addLikeValue($aValue)
    {
        return ('%' . $aValue . '%') ;
    }
    //
    // Ajouter le mot clé "where", "and" ou "or.
    protected function addKeyword($forceAndKeyword = false)
    {
        $combination = ( ( $this->andCombinationFlag || $forceAndKeyword ) ? 'AND' : 'OR' ) ;
        $keyword = ( $this->whereFlag ? $combination : 'WHERE' ) ;
        if( ! $this->whereFlag ) {
            $this->whereFlag = true ;
        }
        return ($keyword . ' ') ;
    }
    //
    // *************************************************************************
    //
    // Gestion des paramètres pour la requête DQL.
    //
    // *************************************************************************
    //
    // Ajouter un paramètre "de type date" et sa valeur (à la requête).
    protected function addQueryParameterDate($aParamValue, $aValue)
    {
        $this->queryParametersDate[ $aParamValue ] = $aValue ;
    }
    //
    // Ajouter un paramètre et sa valeur (à la requête).
    protected function addQueryParameter($aParamValue, $aValue)
    {
        $this->queryParameters[ $aParamValue ] = $aValue ;
    }
    //
    // Valoriser les paramètres de la requête, le cas échéant.
    protected function addQueryParameters($aQuery)
    {
        if( count($this->queryParameters) > 0 ) {
            $aQuery->setParameters($this->queryParameters) ;
        }
        foreach( $this->queryParametersDate as $param => $value )
        {
            $valueArray = explode('-', $value) ;
            $dateValue = new \DateTime($valueArray[2] . '-' . $valueArray[1] . '-' . $valueArray[0]) ;
            $aQuery->setParameter($param, $dateValue, \Doctrine\DBAL\Types\Type::DATETIME) ;
        }
    }
    //
    // *************************************************************************
    //
    // Gestion des paramètres passés via le formulaire.
    //
    // *************************************************************************
    //
    // Test l'existence du paramètre.
    protected function parameterExists($aParameter)
    {
        
    }
    //
    // *************************************************************************
    //
    // Gestion des paramètres passés dans l'URL.
    //
    // *************************************************************************
    //
    // Récupérer les paramètres passés dans l'URL (c-à-d les filtres).
    protected function initUrlParameters(Request $request)
    {
        foreach( $this->namesUrlParameters as $name )
        {
            $value = $request->query->get( $name ) ;
            if( $value != "" )
            {
                $this->setUrlParameters($name, $value) ;
            }
        }
        
        $this->initCombinationFlag($request) ;
    }
    //
    // Tester l'existance d'un paramètre.
    protected function parametersExists($aNameParam)
    {
        return array_key_exists($aNameParam, $this->urlParameters) ;
    }
    
    private function initCombinationFlag(Request $request)
    {
        if( $this->parametersExists(self::CONST_OP_CONTRAINTE) )
        {
            $valueOpCombi = $this->urlParameters[ self::CONST_OP_CONTRAINTE ] ;
            
            $this->andCombinationFlag = ( ($valueOpCombi == 'ou') ? false : true ) ;
        }
    }
    
    protected function addNamesUrlParameters($aKey, $aUrlParam)
    {
        $this->namesUrlParameters[ $aKey ] = $aUrlParam ;
    }
    
    protected function getUrlParameters()
    {
        return $this->urlParameters ;
    }
    
    protected function setUrlParameters($key, $valueParam)
    {
        $this->urlParameters[ $key ] = $valueParam ;
    }
}
