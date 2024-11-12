### Booksinder
**Booksinder** est une plateforme web permettant aux particuliers d'échanger des livres entre eux. L'utilisateur peut ajouter des livres à échanger, rechercher des livres proposés par d'autres utilisateurs et gérer ses annonces. Cette application est construite avec Symfony et utilise VichUploader pour la gestion des fichiers d'images.

* Table des matières
- Description
Prérequis
Installation
Configuration
Utilisation
Contribuer
Description
Booksinder est une application Symfony permettant aux utilisateurs d'ajouter des livres à échanger, de rechercher des livres proposés par d'autres utilisateurs, et de gérer leurs annonces. Elle offre des fonctionnalités comme l'authentification des utilisateurs, la gestion des images, et la validation de formulaires.

Prérequis
Avant d'installer ce projet, assurez-vous d'avoir installé les outils suivants sur votre machine :

PHP 8.x ou plus
Composer
Symfony
Un serveur web (par exemple, Apache ou Nginx)
MySQL ou MariaDB pour la base de données
Installation
1. Cloner le repository
Clonez ce projet en utilisant Git :

git clone https://github.com/username/booksinder.git
2. Installer les dépendances
Dans le répertoire du projet, exécutez la commande suivante pour installer les dépendances PHP :

composer install

3. Créer et configurer la base de données
Configurez votre base de données dans le fichier .env ou .env.local. Modifiez la ligne suivante avec vos informations de base de données :

DATABASE_URL="mysql://username:password@127.0.0.1:3306/booksinder_db"
Ensuite, exécutez les migrations pour créer les tables dans la base de données :

php bin/console doctrine:migrations:migrate

4. Configurer les permissions d'écriture pour les fichiers
Si vous utilisez VichUploader pour la gestion des images, assurez-vous que le dossier de téléchargement est accessible en écriture par le serveur web.

mkdir -p public/uploads
chmod -R 777 public/uploads
Configuration
Aucune configuration particulière n'est requise à part la configuration de la base de données et des fichiers de téléchargement comme décrit ci-dessus.

Utilisation
Lancer le serveur Symfony :

Pour démarrer le serveur local de Symfony, exécutez :

symfony serve

Accéder à l'application :

Vous pouvez maintenant accéder à l'application dans votre navigateur en allant sur http://localhost:8000.

Créer un compte utilisateur :

Vous pouvez créer un compte en vous inscrivant via le formulaire d'inscription et commencer à ajouter des livres à échanger.

Contribuer
Les contributions sont les bienvenues ! Si vous souhaitez contribuer au projet, veuillez suivre ces étapes :

Fork ce projet.
Créez une branche pour votre fonctionnalité (git checkout -b feature/ma-fonctionnalite).
Faites vos modifications et validez-les (git commit -am 'Ajout de ma fonctionnalité').
Poussez votre branche (git push origin feature/ma-fonctionnalite).
Ouvrez une pull request.

Exemple d'utilisation de ce modèle dans le contexte de Booksinder :
Ajout de livre : L'utilisateur peut télécharger une photo de couverture de livre et entrer les détails comme le titre, l'auteur, et une description.
Recherche de livre : Les utilisateurs peuvent rechercher des livres par titre, auteur, ou catégorie.
Échange de livres : Les utilisateurs peuvent entrer en contact pour organiser un échange.
