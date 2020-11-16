# La migration avec Doctrine

## Préparation de la migration

### Installation de doctrine-migrations

La mise en place de la migration avec Doctrine requiert donc la bibliothèque doctrine-migrations. 

> Quid d'orm-pack ?

Ce pack Symfony permet l'installation de Doctrine sur les projets Symfony à partir de la version 4. Il a lui-même besoin, à son installation, de plusieurs bibliothèques dont  doctrine-migrations.
**L'installation d'orm-pack permet l'installation de doctrine-migrations.** 
Sinon, il faudra lancer cette commande depuis le terminal à la racine du projet : ` composer require doctrine/doctrine-migrations-bundle `
### Configuration
Doctrine a besoin de deux fichiers à la racine du projet :

 1. un fichier donnant les informations sur les migrations, migrations.php
 Il suffira de copier/coller la configuration par défaut :
```php
<?php  
return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'version_column_length' => 1024,
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],  

    'migrations_paths' => [
        'App\Migrations' => 'src/Migrations',
    ],  

    'all_or_nothing' => true,
    'check_database_platform' => true
];
```
* *table_storage* est utilisé par doctrine-migrations pour suivre les migrations qui sont actuellement exécutées.
* *all_or_nothing* est un paramètre permettant d'exécuter ou pas plusieurs migrations en une seule fois.
* *check_database_platform* est un paramètre déterminant la vérification ou pas de la plateforme d'abstraction de la base de données (plus d'informations [ici](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.4/reference/platforms.html) ) au début du code généré.

Le fichier peut également être écrit au format xml, json ou yaml (sous réserve d'avoir installé le composant yaml). 


 2. un fichier php retournant les paramètres de connexion à la base de données
     - S'il existe déjà un fichier de configuration au format php, il faudra le préciser avec l'option `--db-configuration *nomdufichier*` lors de l'utilisation de la commande `doctrine:migrations`
      -  Sinon, Doctrine recherche automatiquement le fichier migrations-db.php pour se connecter.
        - Ce fichier doit renvoyer un tableau contenant les paramètres suivants :
     		- le nom de l'hôte
     		- le port pour la connexion
     		- le nom de la base de données
     		- le nom de l'utilisateur accédant à la base de données
     		- son mot de passe
     		- le nom du driver (ici, pdo_mysql)
     	- Pour ce faire, il est possible de copier-coller ce code dans le fichier migrations-db.php
	
		``` php
		<?php
			require 'vendor/autoload.php';
			require 'public/include/config.php';
			use Doctrine\DBAL\DriverManager;
			use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
			use Symfony\Component\Console\Helper\HelperSet;

			$dbParams = array(
			    'dbname' => $config['dbname'],
			    'user' => $config['dbuser'],
			    'password' => $config['dbpass'],
			    'host' => $config['dbhost'],
			    'port' => $config['dbport'],
			    'driver' => 'pdo_mysql'
			);

			return $dbParams;
        ```


Pour terminer la configuration, il faudra modifier le fichier **doctrine_migrations.yaml** situé dans le répertoire config/packages :

``` yaml
doctrine_migrations:
    #dir_name: '%kernel.project_dir%/src/Migrations'
    # namespace is arbitrary but should be different from App\Migrations
    # as migrations classes should NOT be autoloaded
    #namespace: DoctrineMigrations
    migration_paths:
        'App\Migrations': '%kernel.project_dir%/Migrations'

    connection: default
    
    em: default
    
    storage:
        # Default (SQL table) metadata storage configuration
        table_storage:
            table_name: 'doctrine_migration_versions'
            version_column_name: 'version'
            version_column_length: 1024
            executed_at_column_name: 'executed_at'
            execution_time_column_name: 'execution_time'

    # Possible values: "BY_YEAR", "BY_YEAR_AND_MONTH", false
    organize_migrations: false

    # Path to your custom migrations template
    custom_template: ~

    # Run all migrations in a transaction.
    all_or_nothing: true

    # Adds an extra check in the generated migrations to ensure that is executed on the same database type.
    check_database_platform: true
```



## Création d'une migration

Toute migration devra être définie dans un fichier placé dans le répertoire **src/Migrations**.

Il est possible de générer un squelette de fichier de migration avec la commande `php bin/console doctrine:migrations:generate`. 

* Si bin/console n'est pas accessible depuis le terminal, il faudra écrire la commande suivante : `./vendor/bin/doctrine-migrations generate` 
* Les migrations sont nommées automatiquement à la date et l'heure de la création du fichier, ce qui évite d'avoir des conflits de fichiers sur master. Il est toutefois possible de changer le nom de version.  Au même titre pour un controller, il faut que le nom du fichier soit cohérent avec le nom de la classe.

Voici une migration générée par doctrine-migrations :

```php
<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116153145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

```

* `declare(strict_types=1);` est une directive servant à éviter bon nombre de bugs, notamment en testant le type de la variable.
* On observe trois méthodes, qui sont le minimum requis pour une migration :
  * `getDescription()` : on peut forcer l'affichage de la description en changeant le retour de la fonction. La description s'affiche avec la commande `php bin/console doctrine:migrations:list` ou `./vendor/bin/doctrine-migrations :list`.
  * `up()` et `down()`, qui sont appelées respectivement par la commande  `./vendor/bin/doctrine-migrations execute 'nomdelaversion' --up ` et `./vendor/bin/doctrine-migrations execute 'nomdelaversion' --down` . 
    * `up()` détecte la migration qui n'a pas exécutée et d'opérer les changements écrits dans la fonction en base de donnée.
    * `down()` détecte la migration précédemment exécutée pour défaire les modifications écrites dans la méthode up.
    * Les méthodes à appeler dans ces deux fonctions sont :
      * `addSql`: permet de passer une requête SQL et de l'exécuter dans le DBAL. Dans la requête, il sera possible de passer un tabler pour réaliser des insertions simultanées dans la table, par exemple.
      * `abortIf` : interrompt la migration à une condition donnée.
      * `skipIf` : passe la migration à une condition donnée.
      * `throwIrreversibleMigrationException` : *dans la méthode down() seulement*, renvoie une exception pour signaler qu'on ne peut pas revenir à la version précédant la migration.
      * `warnIf` : envoie un message à une condition donnée.
      * `write` : écrit une information dans la console (utile pour le débogage).

Il est possible d'affiner le comportement de la migration en implémentant des méthodes appelées avant ou après up/down : `preUp`, `preDown`, `preDown`, `postDown`.

## Manipulation des migrations

Une fois la migration préparée, il existe différentes interactions avec doctrine-migrations, que l'on peut afficher avec la commande ` ./vendor/bin/doctrine-migrations list`  (attention, à ne pas confondre avec :list, qui va afficher la liste des migrations).

Certaines sont à relever :

* `./vendor/bin/doctrine-migrations status`, qui affiche le statut des migrations
* `./vendor/bin/doctrine-migrations execute`, qui exécute une ou plusieurs migrations (il faudra donner le nom de chaque migration). Avec l'option --up ou --down, on peut réaliser une migration ou la défaire. Le tout est fait manuellement
* `./vendor/bin/doctrine-migrations migrate 'nom_de_la_migration'`, qui effectue la migration de notre choix. Si l'on ne met pas de nom, la migration exécutée par défaut est la dernière version disponible.
* `./vendor/bin/doctrine-migrations up-to-date`, qui affiche si l'on est ou pas à jour.
  * <u>**Attention :**</u> si une mise à jour est réalisée manuellement, directement dans la base de données, elle n'est pas détectée et le schéma sera considéré comme "pas mis à jour". Il faudra peut-être ajouter la version dont on a reproduit le comporement de la méthode `up`, avec la commande `./vendor/bin/doctrine-migrations version --add 'nom de la version'` 
