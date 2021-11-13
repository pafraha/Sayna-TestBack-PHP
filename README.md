## SAYNA TestBack PHP

#### Description

- Application hebergé au Heroku avec "https://sayna-api-mongo.herokuapp.com"
- Avec Base de données Atlas mongodb on line "sayna_api"
- Versionner au Github avec lien : "https://github.com/pafraha/Sayna-TestBack-PHP"
- Documentation générer avec *scribe*

#### Installation

- Cloner le projet et procède direct à son installation
- Configurer base de donnée dans le fichier *.env* à éditer avec attention :

  ```
  DB_CONNECTION=mongodb # Type de connection 
  MONGO_DB_DSN=mongodb+srv://user:<password>@sayna.mprry.mongodb.net/<nom_de_base_de_donnee>?retryWrites=true&w=majority
  MONGO_DB_DATABASE=<nom_de_base_de_donnee> # Nom du base de donnée (Optimal puisqu'il figure déjà dans l'URL DSN)
  ```
- puis installer les dépendances composer "*composer install*"
- Migrer les tables par la commande "*php artisan migrate*"
- Après lancer le server par "php artisan serve" ou se rendre dans *public/*

#### Documentation existe dans le lien "*/docs*" du page d'accueil

Avec les informations sur les endpoints
