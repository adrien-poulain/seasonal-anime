# SEASONAL ANIME

Il s'agit d'un planning des animés que vous aurez programmé dans l'interface ADMIN

## Prérequis

- [Composer](https://getcomposer.org/download/)
- [XAMPP](https://www.apachefriends.org/download.html) (PHP 8.2 minimum !)
- [Symfony-CLI](https://symfony.com/download)

## Installation

- Installation des prérequis
- FORK/CLONE le projet
- Ouvrir un terminal dans le dossier du projet
- Tapez la commande suivante :
```bash
composer update
```
- Dans le XAMPP sur la ligne APACHE cliquez sur 'Config' puis php.ini
- Trouvez la ligne ";extension=gd" et retirer le ";" devant pour décommenter la ligne
- Relancer votre ordinateur pour que la modification soit prise en compte
- Démarrer le serveur APACHE et MYSQL dans XAMPP
- Ouvrir un terminal dans le dossier du projet
- Tapez les deux commandes suivantes:
```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```
- Vous aurez ainsi les tables ainsi que la base qui seront générées dans le serveur MySQL
- Pour finir vous devrez une fois de plus dans le terminal dans le dossier du projet taper la commande pour démarrer le serveur symfony : 
```bash
symfony server:start
```

Profitez bien de ce projet !