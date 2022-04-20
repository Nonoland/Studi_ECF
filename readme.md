# Installation du projet en local

## Prérequis

- PHP 8.1
- Composer (dernière version en date)
- MySQL (>=8.0) ou MariaDB (>=18.2)

## Récupérer le git sur votre espace local

```
git clone https://github.com/Nonoland/Studi_ECF.git
```

## Installer les dépendances composer

### Connecter le serveur SQL :

Changer les paramètres de connexion au serveur SQL dans le fichier **.env.local.php**, ligne **DATABASE_URL**.

### Pour la production (PROD) :
```
composer install --no-dev
```

### Pour le développment (DEV) :
```
composer install
```
Changer la valeur de **APP_ENV** de *PROD* en *DEV* dans le fichier **.env.local.php**

## Lancement du serveur web en local

```
php bin/console server:start
```
Ajouter l'option `-d` pour avoir le processus détaché de votre terminal

Accéder au site avec `http://localhost:8000`

### Créer un compte administrateur

Après avoir lancé le serveur web. Exécuter la commande suivante :

```
php bin/console app:create-admin <mail> <password>
```

Vous pourrez alors vous connecter à l'espace d'administration depuis `http://localhost:8000/connexion` et en cliquant sur le bouton **Administration**
