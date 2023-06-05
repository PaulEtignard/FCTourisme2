
# **FC-TOURISME**
## Présentation du projet 
Le conseil régional de Franche-Comté souhaite promouvoir les établissements touristiques de la région. Pour ce faire, il souhaite développer un site internet permettant aux propriétaires d'établissements de la région de présenter leurs établissements afin de mieux les faire connaître.

Un établissement touristique est un lieu pouvant accueillir du public. Il peut s’agir d’un restaurant, d’un hôtel, d’un gîte, d’un musée ou d’un artisan proposant des visites de ces activités.

Chaque propriétaire pourra, via un espace sécurisé et simple d’utilisation, gérer son ou ses établissement(s).

Les utilisateurs du site auront la possibilité de visualiser les établissements mis en ligne.

Chaque utilisateur aura également la possibilité de se créer un compte utilisateur. Il pourra ainsi mettre en favori des établissements, noter des établissements, gérer ses favoris, contacter des établissements par mails et d’autres fonctionnalités qui seront détaillées ultérieurement.

Le conseil général vous confie la réalisation du site après un appel d’offre que votre société a remporté.

## Présentation des différents outils utilisé

 - *Langage de programmation :* Php
 - *Framework :* Symfony 6
 - *Gestionnaire de version* : Git / GitHub
 - *SGBD :* MySQL sous XAMPP
 - *Documentations :*
	 - https://symfony.com/doc/current/index.html
	 - https://twig.symfony.com/doc/
	 - https://www.php.net/manual/fr/index.php

## Mise en place du projet

commandes a effectuer après l'importation avec GitHub :

 - Composer install
 - Symfony console d:d:c
 - Symfony console doctrine:fixtures:load --purge-exclusions=ville --purge-exclusions=categorie
 - Ajout des catégorie dans la base grâce a cette requête : 
	 - INSERT INTO `categorie` (`id`, `nom`, `created_at`) VALUES (NULL, 'Restaurant', '2022-11-29 22:22:38.000000'), (NULL, 'hotel', '2022-11-29 22:22:38.000000'), (NULL, 'Gites', '2022-11-29 22:22:38.000000'), (NULL, 'Artisanat', '2022-11-29 22:22:38.000000'), (NULL, 'Musée', '2022-11-29 22:22:38.000000');
- Symfony console doctrine:fixtures:load --purge-exclusions=ville --purge-exclusions=categorie
