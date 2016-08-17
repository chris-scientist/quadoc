<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class SearchController extends Controller
{
    //
    // true si la clause "where" a été ajouté à la requête, 
    // et false si la clause "where" n'a pas été ajouté (par défaut).
    private $whereFlag ;
    //
    // true si les contraintes sont liées par un "et logique" (par défaut),
    // et false si les contraintes sont liées par un "ou logique".
    private $andCombinationFlag ;
    //
    // Tableau composé d'un groupe de contrainte.
    private $constraints ;
    
    public function __construct()
    {
        $this->whereFlag = false ;
        $this->andCombinationFlag = true ;
        
        $this->constraints = array() ;
    }
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
        
        for( $itC = 0 ; $itC < count($this->constraints) ; $itC++ ) {
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
    protected function addConstraintIsNull($aField, $isNull = true)
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
    // Contraintes relatives au attribut pouvant valoir plusieurs valeurs (uniquement pour les clés).
    // -------------------------------------------------------------------------
    // 
    // 
    protected function addConstraintIn($aField, $aArrayValue)
    {
        $constraint = $aField . ' IN ' ;
        $constraint .= $this->addInValue($aArrayValue) ;
        return $constraint ;
    }
    // 
    // -------------------------------------------------------------------------
    // Contraintes relatives au zone de saisie libre, autrement dit au texte.
    // -------------------------------------------------------------------------
    //
    // Ajouter une contrainte "like" sur un champ.
    protected function addConstraintLike($aField, $aParamValue)
    {
        $constraint =   'UPPER(' . $aField . ') ' .
                        'LIKE ' .
                        'UPPER(:' . $aParamValue . ') ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "=" (égale à) sur un champ.
    protected function addConstraintTextEqualTo($aField, $aParamValue)
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
    protected function addConstraintDateBtw($aField, $aParamBegin, $aParamEnd)
    {
        $constraint = $aField .
                        ' BETWEEN :' . $aParamBegin .
                        ' AND :' . $aParamEnd . ' ' ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "jusqu'à" une date (aParamValue).
    protected function addConstraintToDate($aField, $aParamValue)
    {
        $constraint = $aField .
                        ' <= :' .
                        $aParamValue ;
        return $constraint ;
    }
    //
    // Ajouter une contrainte "depuis" une date (aParamValue).
    public function addConstraintFromDate($aField, $aParamValue)
    {
        $constraint = $aField .
                        ' >= :' .
                        $aParamValue ;
        return $constraint ;
    }
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
    // Groupe de valeur.
    private function addInValue($aArrayValue)
    {
        $value = '(' ;
        for( $itV = 0 ; $itV < count($aArrayValue) ; $itV++ )
        {
            if( $itV > 0 && $itV < count($aArrayValue) ) {
                $value .= ', ' ;
            }
            $value .= $aArrayValue[ $itV ]->getId() ;
        }
        $value .= ') ' ;
        return $value ;
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
    
    protected function initCombinationFlag($form)
    {
        $andCombination = $form['combination']->getData() ;
        $this->andCombinationFlag = $andCombination ;
    }
}
