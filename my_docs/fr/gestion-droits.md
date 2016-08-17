
# Quadoc

Dernière modification : 17/08/2016
Auteur(s) : C. Thubert (chris-scientist)

## Gestion des droits

### Présentation

La hiérarchie des rôles est définit dans le fichier suivant (à la fin de celui-ci : "role_hierarchy") : 
app/config/security.yml

Les droits sont gérés à deux niveaux :
- Dans les vues : pour masquer une partie à un utilisateur n'ayant pas les droits suffisant.
- Dans le contrôleur : pour interdire l'accès à une page lorsque l'utilisateur n'a pas les droits suffisant.

Dans la vue les droits sont testés avec :
is_granted("ROLE_ADMIN")

Dans le contrôleur pour tester les droits, il faut inclure la classe :
Sensio\Bundle\FrameworkExtraBundle\Configuration\Security

Dans le contrôleur les droits sont testés avec l'annotation suivante, qui est déclaré sur une méthode :
@Security("has_role('ROLE_ADMIN')")

Pour la création (ou la mise à jour) d'un utilisateur, la liste des rôles a été définit en dur dans la classe :
src/AdminBundle/Form/UtilisateurType.php

### Avancement (exemples)

Les droits sont gérés pour :
- (1) les équipements, les contrats (relatifs aux équipements), et les interventions ;
- (2) la partie administration.

Les droits gérés en (1) sont écrit dans les contrôleurs suivants (src/DechetEquipementBundle/Controller/) :
- EquipementController.php ;
- ContratController.php ;
- InterventionController.php.

Et également dans les vues suivantes (app/Resources/views/equipement/) :
- index.html.twig ;
- show_contrat.html.twig ;
- show_intervention.html.twig.

Les droits gérés en (2) sont écrit dans tout les contrôleurs situés dans :
src/AdminBundle/Controller/

Ainsi que dans la vue suivante :
app/Resources/views/base.html.twig
