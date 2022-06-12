# Projet de formation

Documentation complète du projet de formation Symfony Progica. L'idée est d'avoir un reccueil "step by step" permettant de refaire le projet à l'identique et de comprendre son cheminement.
Note,  le projet à été refait en cours de route à cause d'un problème de connection au serveur.

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

- ``symfony serve``
- ctrl + c OU `php bin/console server:stop` si server already running

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
- **Symfony\Component\HttpFoundation\Response** permet à notre controller de fournir une reponse à la route demandée pour afficher le template.
- **Symfony\Bundle\FrameworkBundle\Controller\AbstractController** permet d'utiliser par la suite la méthode render() dans nos fonctions de classes. Ce qui rend plus simple et plus lisible le code pour transmettre la vue demandée.
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

## Routes annotations et twig
- Dans **HomeController.php** implémenter `use Symfony\Component\Routing\Annotation\Route;`
- Si ce n'est pas implémenté utiliser la commande `composer require annotations`
- Au dessus de chaque fonction public dans **HomeController.php** :

`/**`
  
`*@Route("nomDeLaRouteDansL'URL", name="nomController_nomFonction")`

`*/`

- Le `name`sera utiliser dans twig dans le `{{ path('nom_route') }}` pour créer des liens dans la navbar par exemple
- `php bin/console debug:router` : pour avoir des informations sur nos routes
- Décorons les liens twig avec la classe `active`:
  - **HomeController.php** > 
  
  `render('fichier.ext', [`

  `'menu' => 'home'`

  `]);`

  - **base.html.twig** > `class=" {% if menu == 'home' %} active {% endif %}"`
  - Même chose pour contact.

## Doctrine BDD

## Configuration fichier .env

- Ligne 31 : postgresql mis en commentaire;
- Ligne 30 : ``DATABASE_URL="mysql://root@127.0.0.1:3306/progica?serverVersion=10.4.22-MariaDB&charset=utf8mb4"``

### A cause du problème de version (résolu):
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

### Problème d'affichage (résolu)

Bootstrap me fait un comportement curieux avec les cards, elles se supperposent sur le tier de l'espace. Le row ne prend qu'un tier de son container.
Problème résolu : c'était un `style="width: 18rem;"` glisser dans la  div de ma row.


# Le CRUD

Create, Read, Update, Delete.
Dans cette partie on va créer un système de CRUD dans une partie administration. Cette partie permettra de manager les gites.

## Création de la partie admin

- Dans **src > Controller** on créer **Admin/AdminController.php**
  - `namespace App\Controller\Admin`
  - une public foncton qui renvoi au templates :
  - **templates** > création du dossier **admin** > création du fichier **index.html.twig**
  - **templates** > création du fichier **dashboard.html.twig** que l'on extends à **admin/index.html.twig**
- Dans **AdminController.php** sur `@Route` faire Ctrl + Alt + i pour importer le component d'annotation de route.
- On rend "jolie" avec Bootstrap et on affiche nos gites à partir de la BDD :
  - **AdminController.php** ajouts :
    - `use App\Entity\Gite;` : pour avoir accès à nos méthodes d'affichage des gites
    - `use Doctrine\Persistence\ManagerRegistry;` : pour avoir accès à notre BDD.
    - `index(ManagerRegistry $doctrine)` : pour que notre fonction ait accès à la BDD
    - `$repository = $doctrine->getRepository(Gite::class);` : Lié les méthodes de class au objets de la BDD
    - `$gites = $repository->findAll();` : générer un tableau d'objets
    - `'gites' => $gites` : ligne ajouté aux attr `render()` pour manipuler les objets en twig :
  - **admin/index.html.php** :
    - `{% for gite in gites %}` : dans le tbody d'un tableau pour parcourir nos objets
    - `<td>{{ gite.id }}</td>` : syntaxe pour les afficher
    - ``{% endfor %}`` : pour finir.
    - Des buttons **Modifier** et **Supprimer** ont été ajouté pour chaque éléments du tableau

## GiteController.php

Pour gérer les actions des buttons **Modifier** et **Supprimer** (mais peut-être aussi d'autres méthodes utiles) on va séparer le code et créer un nouveau fichier dans **Controller > Admin > GiteController.php** ave une fonction ``show`` qui affiche nos gites selon leur id passé en paramètre de la route. Puis on créer dans templates **gite > show.html.twig** avec l'extends **base.html.twig**. Pour que la variable menu ne nous pose pas de problème on utilise une condition twig dans la navbar : `if not (menu is defined) or` + condition précédente. On créer la vu "show" d'un gite et on l'affiche avec un des id déjà présent dans notre BDD pour voir si ça marche.

### Problème d'affichage (résolu)

En cliquant sur les cards dans home la première donne un # dans l'url et les deux autres une erreur `Impossible to access an attribute ("nom") on a null variable.`
Résolution : dans le "show twig" `gite.id` dans le path ``id: gite.id`` était noté entre " ".
Forcément la machine attendait un int et reçevait une chaîne de caractère.

## Créer un Gite à partir de la plateforme

Pour le client qui ne sait pas coder mais qui administre le site (principe d'un cmd avec back-office quelque part).


# Todo temporaire :

- Voir comment lier ce repo à github branche main
- Supprimer seconde branche






