## SITE VITRINE

Pour créer le site vitrine avec le plugin «MoodlePE» suivez les étapes suivantes : 
1. Créer un fichier « index_old.php» à la racine de votre dossier. (Si votre projet s'appelle «Moodle», le chemin vers votre fichier « index_old.php» sera « Moodle/index_old.php»).

2. Copiez tout le code du fichier «Moodle/index.php» dans le nouveau fichier créé. (Celà est très important pour que vous puissiez vous rappeller le nom des fichiers).

3. Copier le contenu du fichier « Moodle/local/moodlepe/showcase/index.php» dans votre fichier     «Moodle/index.php».

4. Copier le dossier «ashleyshowcase» a la racine du projet ( le chemin sera : «moodle/ashleyshowcase»)

5. Changer l'url vers la page de connexion de votre projet dans le nouveau fichier index qui est à la racine (ctrl+f pourra vous aider à trouver le mot connexion et à changer l'url passer) et pour les redirection des offres
    *   Si votre votre projet s'appelle «moodle» vous écrirez : «href="/moodle/login/index.php"» 
    *   Si votre projet s'appelle «moodlepe» vous écrirez : «href="/moodlepe/login/index.php"»

6. Exécutez le fichier «install.php »du plugin s'il n'est pas exécuté automatiquement

7. Changer le chemin de retour en cas d'erreur dans le fichier «Moodle/local/moodlepe/register/index2»
Il existe 6 redirections sous cette forme : 
«header("Location: /moodlepe/local/moodlepe/register/index.php?erreur=$usernameerror&offre=$offer_id");»

Changer le «moodlepe/local» en «moodle/local» si votre projet s'appelle «moodle»


## BASE DE DONNEE

Vous devrez éditer le fichier « Moodle/local/moodlepe/edit_database_name.php».
Dedans y sont inscrit les informations pour communiquer avec votre base de donnée. Vous devez editer les variables suivantes : 

1. $hostname de la fonction : « function database_hostname() »
Cette fonction retourne le hostname de votre SGBD (si rien n'y est inscrit, elle retourne la valeur «localhost»)

2. $username de la fonction : « function database_username() »
Cette fonction retourne le nom d'utilisateur de votre SGBD

3. $password de la fonction : « function database_password() »
Cette fonction retourne le mot de passe de votre SGBD

4.  $dbname de la fonction : « function database_name() »
Cette fonction retourne le hostname de votre base de donnée 

5. $port  de la fonction : « function database_port() »
Cette fonction retourne le port que utilise PhpMyAdmin

 