# Projet de formation

Documentation complète du projet de formation Symfony Progica. L'idée est d'avoir un reccueil "step by step" permettant de refaire le projet à l'identique et de comprendre son cheminement.

# IDE

- php 8.1 (bien que composer charge le 8.0)
- symfony 6.0.*
- composer 2.3.5
- git et github
- Bootqtrap 5

## Vérifier environnement et projet en ligne de commande

- php -v
- composer -v
- symfony
- git log ("q" pour quitter) / git status

## Lancer/quitter serveur

- symfony serve
- ctrl + c 

## Pb de version php

- composer install --ignore-platform-reqs
- (Ps : pb de version réglé plus bas : section **Doctrine BDD**)

# Avancée du projet

## Sommaire :
- Initialisation de **git** et création/connection à un répertoire **github**.
- Création des premiers **controllers**, **routes** et **twig-templates** pour les pages "home" et "contact".
- Création de la database et de notre première entité avec Doctrine
- Récupération et utilisation des données dans la BDD
- Création d'un CRUD


## Git et GitHub

#### Rappels :
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pour le projet en local :
- **git init** : initialiser un projet
- **git add -A** : enregistrer tous les fichiers. Ensuite on utilisera **git add .** ou **git add dossier/fichier.ext dossier2/fichier2.ext**.
- **git commit -am "First commit"** : la première version de notre projet (si on casse le code on peut ainsi revenir à une version antérieure). Le a permet d'enregistrer tous les fichiers passés dans le dernier git add et m de laisser un message de commit : "Exemple du First commit".
- **git push** : pour "pousser" nouveau commit vers répertoire.

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pour le répertoire :

- **git remote add origin https://github.com/leni-mv/Progica.git** : Lier répertoire créer spécifiquement sur github à notre projet
- **git branch -M main** : permet de changer le nom d'une branche mais aussi de l'initier (apparemment)
- **git push -u origin main** : Le premier push de notre projet vers la branche principal de notre répertoire.

## Controllers

- Dans **src>Controller** créer fichier **HomeController.php** qui contiendra les controllers de la navbar.
- Le namespace **App\Controller** permettra d'appeler le fichier dans **routes.yaml** plus tard.
- **Symfony\Component\HttpFoundation\Response** permet la création de notre class en controller.
- **Symfony\Bundle\FrameworkBundle\Controller\AbstractController** permet d'utiliser par la suite la méthode render() dans nos fonctions de classes. Ce qui rend plus simple et plus lisible le code.
- On extends la classe **AbstractController** à la classe **HomeController** (qui va contenir tous nos controllers) de navbar pour utiliser la méthode render().
- On créer nos controller dans des functions public :

`return $this->render(dossier?/fichier.html.twig)`. Ici la méthode **render()** va directement chercher dans le dossier **templates** donc pas besoin de le spécifier dans la route qu'on lui passe en paramètre.

## Routes

- config>routes.yaml :

`nomController:`

  ----`__path__: /nomURL`
  
  ----`__controller__: namespace\nomClasseController::nomFonctionPublic`

## twig-templates

- dossier **templates** :
- Création du dossier **home** pour nos pages home et contact.
- Création des fichiers **index.html.twig** pour home et **contact.html.twig** pour contact.
- Dans chaque fichier on importe **base.html.twig** à la racine du dossier templates : `{% extends "base.html.twig" %}`.
- Dans **base.html.twig** : implémentation CDN de bootstrap, importation d'une navbar et création des liens "home" et "contact" dans la navbar.

## Configuration fichier .env

- Ligne 27 : postgresql mis en commentaire;
- Ligne 26 : décommenté et server MariaDB sur xamp configurée.

## Doctrine BDD

### A cause du problème de version :
- Installation de doctrine requis :
`composer require symfony/orm-pack` et `composer require --dev symfony/maker-bundle`.
- Création de notre première entité Gites :
`$ php bin/console doctrine:database:create`.
- Problème de version (PHP 8.1 alors qe le projet requiere php 8.0) à empêché la commande d'être recconnue > changement de version dans le path (variables d'environnements) > php.ini a configuré.
- Résolution du pb en décommentant `windows extension_dir`, `openssl` (mais aussi `pdo mysql`, `inl..`, `gettext`, `mbstring`)

Ps : Si l'environnement est correctement installé et paramétré en amont du projet il n'y a pas besoin de faire toutes ces manips.

### Création de la BDD Progica : 

- Lancer xamp > mysql.
- Lancement de la commande `php bin/console doctrine:database:create` OU `php bin/console d:d:c` qui crée la database Progica dans le mysql de **Xamp**

### Création de notre première entité :

- `php bin/console make:entity`,
- On nomme l'entité et on créer ses propriétés
- **src > Entity > Gite.php** créée : permet d'avoir des méthodes spécifiques pour chaque attributs (getters et setters préfaits pour chaque propriétés <3)
- **src > Repository > GiteRepository.php** créée : permet essentiellement de faire des requêtes sql avec des méthode doctrine (langage **DQL** : **Doctrine Query Langage**)
- `php bin/console make:migration` : permet de créer un fichier de migration pour notre entité nouvellement créer :
- **migrations > Version20220609... .php** : Ce dossier à la racine de notre projet va contenir toutes nos migrations dans des fichiers Version. Note sur le chiffre en titre du fichier : il s'agit de l'année>mois>jour>heure>minutes>secondes. Le fichier Version mets à notre disposition des méthodes pour intéragir avec notre BDD.
  - Fonction **up->addSql** pour créer notre table avec la requête déjà faites en paramétre.
  - Fonction **down->addSql()** pour supprimer la table. Utile pour revenir à une version précédente par exemple.
- `php bin/console doctrine:migrations:migrate` OU `php bin/console d:m:m` : permet de rendre la migration effective dans la BDD grâce à notre fichier de Version. La BDD possède maintenant :
  - notre Table Gite avec ses attributs et la clé primaire "id"
  - une table contenant nos fichier de migration doctrine

### Oups ! On a oublié de mettre le nombre de couchage
- `php bin/console make:migration Gite` > yes > créer nouvelle propriété "couchage" :
- La propriété "couchage" est ajouté au fichier **src > Entity > Gite.php**.
- Un nouveau fichier de migration est créée en même temps !
- `php bin/console doctrine:migrations:migrate` :
- La base de donnée ajoute la colonne "couchage" à la table Gite et enregistre le nouveau fichier de version dans la table doctrine-version.

### Création de notre premier objet Gite

- Pour afficher le Gite en page d'accueil on le crée dans : **src > Controller > HomeController.php**
- Dans `public function index()` reliée à notre page "home" on instancie `$gite = new Gite();` : la première ligne de notre fonction maintenant.
- On pense à la lier avec un `use` à notre `App\Entity\Gite;`
- En dessous de l'objet $gite on "set" ses propriétés à partir des méthodes de l'entité Gite :
  - `$gite->setname('Mon premier Gite')`
  - `->setDescription('Longue description')`
  - `->etc(..);`
- Pour enregistrer ce nouvelle objet dans la BDD on a besoin de passer en paramétre de notre fonction index() `ManagerRegistery $doctrine`. Ici le paramètre `$doctrine` prends `ManagerRegistery` en type.
- `use Doctrine\Persistence\ManagerRegistry;`
- Ensuite au dessus du nouvel objet `$manager->getManager();` : ce qui permet d'importer les méthode de **ManagerRegistery**
- En dessous de notre objet et ses setters : `$manager = $doctrine->persist($gite);` : cela permet d'enregistrer un tableau temporaire avec les données passées dans les setters de notre objet $gite.
- `$manager->flush()` : crée et lance la/les requêtes pour enregistrer le array de la méthode `persist()`en BDD.
- On enregistre et :
  - Dans profiller on voit l'implémentation du nouvelle objet dans la BDD
  - Dans la BDD un nouvel objet avec nos valeurs est créée ("il est né le divin enfant !!")
  - ATTENTION : Rafraichir la page créer un deuxième objet. (A voir comment qu'on fait pour éviter ce facheux comportement)

### #Note: autowiring

Méthode permettant d'utiliser les composant intégrés à Symfony sans avoir besoin de les appeler dans notre fichier (le namespace en `use` et le nom de la classe voulu suffisent pour utiliser ses méthodes, y compris en argument de fonction public)
`ManagerRegistery` est simple d'utilisation grâce à l'**autowiring**.

### Récupérer les informations en BDD

Pour ce mettre bien :
- Dans **HomeController.php** on mets toute la création de l'objet $gite et $manager en commentaire.
- On garde dan `index()` le paramètre `ManagerRegistery $doctrine`.

Cela nous évitera de recréer un objet à chaque rafraichissement de page !

Ensuite, commençons ! :
- A la place de `$manager` on créer `$repository = $doctrine->getRepository(Gite::class);`
- On enregistre dans `$gite = ` l'élément souhaité dans le répertoire : `$repository->find(id souhaité);`
- `dump($gite);` : pour voir dans notre profiller en dernier icône nos informations. (Pour ça on rafraichie la page biensûr);
On peut mettre `dump()` en commentaire on n'en a plus besoin pour le moment.

## Afficher nos informations
Du controller au template :
- Dans **HomeController.php** function `index()` méthode `$this->render` on peut passer un deuxième paramétre : un tableau en clé => valeur. On passera le nom de la clé à twig pour qu'il affiche la valeur :
  - Sur render : `('file.html.twig', ['gite' => $gite]);`
  - Sur twig : `{{ gite.name }};` par exemple. Cela fonctionne avec toutes les propriétés de la classe **Gite**.
- Pour afficher un tableaux de données :
  - **HomeController.php** : `$gites = $repository=>findAll();` sans paramétre et `['gites' => $gites]`
  - Twig nous permet de boucler sur un tableau : `{% for gite in gites %}` affichage avec `{{ gite.attribut }}` et `{% endfor %}` pour clotûrer.

### Problème d'affichage

Bootstrap me fait un comportement curieux avec les cards, elles se supperposent sur le tier de l'espace. Le row ne prend qu'un tier de son container.
Problème résolu : c'était un `style="width: 18rem;"` glisser dans la  div de ma row.

## Problème de connection au serveur

Depuis la mauvaise installation de la commande `$ symfony server:ca:install` les navigateurs ne peuvent plus se connecter à mon localhost (il refuse la connection en http/https/8080/8000/Ect). Plus de détails ici : https://stackoverflow.com/questions/72515228/the-commande-symfony-servercainstall-have-break-my-connexion-to-my-localhost

# Le CRUD

Create, Read, Update, Delete.
Dans cette partie on va créer un système de CRUD dans une partie administration. Cette partie permettra de manager les gites.

## Création de la partie admin

- Dans **src > Controller** on créer **Admin/AdminController.php**






