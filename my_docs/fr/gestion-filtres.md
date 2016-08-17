
# Quadoc

Dernière modification : 17/08/2016
Auteur(s) : C. Thubert (chris-scientist)

## Gestion des filtres

Par la suite, on peut confondre la notion de filtres et de "recherche avancée".

### Architecture

Pour la construction des formulaires de recheches avancées, 
la classe suivante permet de construire la partie générique des formulaires :
- src/AppBundle/Form/FilterType.php (classe abstraite).

Les autres classes contribuant à la construction des formulaires, 
suivent la nomenclature suivante : 'Filtre', suivi du nom de l'entité, puis 'Type'.
Ces classes se trouvent dans les répertoires suivants :
- src/DechetEquipementBundle/Form/ ;

La vue générique des formulaires est :
app/Resources/views/filter/filter.html.twig

Les autres vues (pour la partie formulaire de recherche) se trouve dans :
app/Resources/views/filter/

On ajoute la vue générique dans les vues suivantes :
- app/Resources/views/equipement/index.html.twig

### Ajouter les filtres à une page

Remarque : un exemple de filtre est disponible pour les équipements.

- Au niveau du contrôleur impacté : remplacer l'héritage Symfony\Bundle\FrameworkBundle\Controller\Controller par AppBundle\Controller\SearchController.
- Dans le bundle relatif à l'entité, dans le répertoire "Form", créer une classe nommée : "Filtre", nom de l'entité, "Type" (FiltreEquipementType, par exemple), cette classe correspond au formulaire des filtres.
- Faire hériter la classe créée de AppBundle\Form\FilterType.
- Ajouter les champs de recherche : pour la recherche textuel utiliser la classe AppBundle\Form\SearchTextType, pour la recherche temporelle utiliser la classe AppBundle\Form\SearchDateType.
- Dans la vue, dans le bloc "body", avant le tableau listant les données ajouter les deux lignes suivantes :
{% set filenameOfNoGenericForm = 'filtre_ENTITE_form.html.twig' %}
{% include 'filter/filter.html.twig' %}
- Remplacer ENTITE par le nom de l'entité ("equipement", par exemple).
- Créer, dans app/Resources/views/filter/, un fichier "filtre_ENTITE_form.html.twig", ce fichier permet d'organiser les champs de recherche.
- Dans la vue, il faut également ajouter dans le bloc "javascripts" le code suivant (à adapter) :
$("#filtre_equipement_toexport").val('off') ;
$("#filtre_equipement_export").click(function () {
    $("#filtre_equipement_toexport").val('on') ;
    $("#filtre_form").submit() ;
}) ;

$("#containeur-filtres").accordion({
    collapsible: true,
    active: false
}) ;

$('.datepicker').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "dd-mm-yy"
}) ;
- Dans le contrôleur, dans la méthode relative à la recherche (potentiellement, "indexAction"), construire la requête relative aux filtres.
