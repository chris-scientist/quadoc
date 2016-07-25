<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class SearchController extends Controller
{
    private $whereFlag ;
    
    private $queryParameters ;
    
    private $queryParametersDate ;
    
    private $urlParameters ;
    
    protected $namesUrlParameters ;
    
    public function __construct()
    {
        $this->whereFlag = false ;
        $this->queryParameters = array() ;
        $this->queryParametersDate = array() ;
        $this->urlParameters = array() ;
        $this->namesUrlParameters = array() ;
    }
    //
    // Récupérer les paramètres passés dans l'URL (c-à-d les filtres).
    protected function initUrlParameters(Request $request)
    {
        foreach( $this->namesUrlParameters as $name )
        {
            $tmpValue = $request->query->get( $name ) ;
            if( $tmpValue != "" )
            {
                $this->setUrlParameters($name, $tmpValue) ;
            }
        }
    }
    //
    // Tester l'existance d'un paramètre.
    protected function parametersExists($aNameParam)
    {
        return array_key_exists($aNameParam, $this->urlParameters) ;
    }
    //
    // Construire la requête (DQL) pour filtrer les données.
    abstract protected function searchQuery(Request $request) ;
    //
    // Ajouter une contrainte "is [not] null" sur un champ.
    protected function addConstraintIsNull($aField, $isNull = true)
    {
        $constraint = $this->addConstraint() .
                        $aField . ' IS ' ;
        if( ! $isNull ) {
            $constraint .= 'NOT ' ;
        }
        $constraint .= 'NULL ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "like" sur un champ.
    protected function addConstraintLike($aField, $aParamValue)
    {
        $constraint = $this->addConstraint() .
                        'UPPER(' . $aField . ') ' .
                        'LIKE ' .
                        'UPPER(:' . $aParamValue . ') ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "=" (égale à) sur un champ.
    protected function addConstraintTextEqualTo($aField, $aParamValue)
    {
        $constraint = $this->addConstraint() .
                        'UPPER(' . $aField . ') ' .
                        '= ' .
                        'UPPER(:' . $aParamValue . ') ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "entre" sur un champ date.
    protected function addConstraintDateBtw($aField, $aParamBegin, $aParamEnd)
    {
        $constraint = $this->addConstraint() . $aField .
                        ' BETWEEN :' . $aParamBegin .
                        ' AND :' . $aParamEnd . ' ' ;
        return $constraint ;
    }
    //
    // Encardrer la valeur par "%".
    protected function addLikeValue($aValue)
    {
        return ('%' . $aValue . '%') ;
    }
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
    // Ajouter le mot clé "where" ou "and".
    private function addConstraint()
    {
        $keyword = ( $this->whereFlag ? 'AND' : 'WHERE' ) ;
        if( ! $this->whereFlag ) {
            $this->whereFlag = true ;
        }
        return ($keyword . ' ') ;
    }
    //
    // -------------------------------------------------------------------------
    //
    // Gestion des paramètres passés dans l'URL.
    //
    //
    protected function getUrlParameters()
    {
        return $this->urlParameters ;
    }
    
    protected function setUrlParameters($key, $valueParam)
    {
        $this->urlParameters[ $key ] = $valueParam ;
    }
}
