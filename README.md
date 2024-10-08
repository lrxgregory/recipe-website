# Docker Symfony

Ce projet utilise Docker pour créer un environnement de développement local pour une application Symfony. Il inclut des services Docker tels que Nginx, PHP-FPM, MySQL, et phpMyAdmin.

## Prérequis

Assurez-vous que vous avez les logiciels suivants installés sur votre machine :

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Contenu du Projet

Ce projet utilise Docker Compose pour gérer plusieurs services nécessaires à l'exécution de votre application Symfony. Voici les services inclus :

- **Nginx** : Serveur web pour servir l'application Symfony.
- **PHP-FPM** : Interpréteur PHP configuré pour fonctionner avec Symfony.
- **MySQL** : Serveur de base de données pour stocker les données de l'application.
- **phpMyAdmin** : Interface web pour gérer facilement la base de données MySQL.

## Installation

Suivez ces étapes pour configurer votre environnement de développement local :

### Étape 1 : Cloner le Répertoire du Projet

Clonez ce dépôt Git sur votre machine locale :

```bash
git clone git@github.com:lrxgregory/docker-symfony.git
cd docker-symfony
```

### Étape 2 : Construire les Images Docker

Construisez les images Docker nécessaires à l'exécution des services :

```bash
docker compose build
```

### Étape 3 : Démarrer les Services

Démarrez les services Docker en mode détaché (en arrière-plan) :

```bash
docker compose up -d
```

### Étape 4 : Installer symfony

Accéder au shell du container php

```bash
#Run this if you are building a traditionnal web application
composer create-project symfony/skeleton:"7.1.*" my_project_directory
cd my_project_directory
composer require webapp

#Run this if you are building a microservice, console application or API
composer create-project symfony/skeleton:"7.1.*" my_project_directory
```

Votre application Symfony sera disponible à l'adresse suivante : http://localhost:8080.

nb: Penser à modifier la ligne dans le fichier nginx.conf si vous changer le "my_project_directory"
=> root /var/www/html/my_project_directory/public;

### Étape 4 : Arrêter les Services

Pour arrêter et supprimer les conteneurs Docker, utilisez la commande suivante :

```bash
docker compose down
```

## Accès aux Services

- **Application Symfony (via Nginx)** : http://localhost:8080

- **phpMyAdmin** : http://localhost:8081
  * Serveur MySQL : mysql
  * Nom d'utilisateur : root
  * Mot de passe : root

## Gestion des Bases de Données

### Connexion MySQL via TablePlus ou Autre Client SQL

Vous pouvez utiliser un client de base de données tel que TablePlus, MySQL Workbench ou DBeaver pour vous connecter à la base de données MySQL en utilisant les informations suivantes :

- Host : localhost
- Port : 3306
- Nom d'utilisateur : user
- Mot de passe : password
- Nom de la base de données : appdb

## Reconstruction des Images Docker

Si vous modifiez le Dockerfile ou docker-compose.yml, vous devrez reconstruire les images Docker pour appliquer les modifications :

```bash
docker compose build --no-cache
```
## Visualiser les Logs

Pour surveiller les logs des conteneurs et déboguer des erreurs potentielles :

```bash
docker compose logs -f
```