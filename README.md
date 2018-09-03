# louvre_project
==============

A Symfony project created on July 10, 2018, 11:13 am.


Code source de l'application de réservation de billets pour le musée du Louvre, réalisée dans le cadre de la formation
"Chef de projet - développement" avec le site Open Classrooms.


# Installation
## 1. Récupérer le code
Vous avez deux solutions pour le faire :

1. Via Git, en clonant ce dépôt : https://github.com/greyven/louvre_project.git
2. Via le téléchargement du code source en une archive ZIP, à cette adresse :
https://github.com/greyven/louvre_project/archive/Dev.zip

## 2. Définir vos paramètres d'application
Pour paramétrer l'application selon vos critères, accès bdd, serveur mail, email du site, et tokens stripe,
le fichier `app/config/parameters.yml` est ignoré dans ce dépôt. A la place, vous avez le fichier `parameters.yml.dist`
que vous devez dupliquer, renommer (enlevez le `.dist`) et modifier.

## 3. Télécharger les vendors
Avec Composer necessaires :

    php composer.phar install

## 4. Créez la base de données
Si la base de données que vous avez renseignée dans l'étape 2 n'existe pas déjà, créez-la :

    php bin/console doctrine:database:create

Puis créez les tables correspondantes au schéma Doctrine :

    php bin/console doctrine:schema:update --dump-sql
    php bin/console doctrine:schema:update --force

