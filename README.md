# Mediatekformation
## Présentation
La présentation du site globale est présente sur [le dépôt GitHub d'origine](https://github.com/cned-slam/mediatekformation?tab=readme-ov-file#mediatekformation).
## Accès au site
Le site est accessible depuis [mediatekformation.jfrancart.fr](https://mediatekformation.jfrancart.fr)
## Présentation des nouvelles fonctionnalités
La partie back-office à été développée complètement. Elle contient les fonctionnalités globales suivantes :<br>
![Cas d'utilisation administration du site](https://github.com/user-attachments/assets/2b96f171-fef5-45df-8189-b657ef1abfd5)
## Les différentes pages
Voici les pages correspondant aux différents cas d’utilisation.
### Page d'authentification
Cette page permet à un utilisateur non authentifié de s'identifier afin de pouvoir accéder aux différentes pages d'administration du site.  
L'utilisateur doit rentrer un nom d'utilisateur, un mot de passe puis cliquer sur le bouton se connecter afin de se connecter.  
Si une erreur lors de l'authentification survient, elle sera visible par l'utilisateur.  
Une fois l'utilisateur connecté avec succès, il est possible de se déconnecter à tout moment en cliquant sur le bouton se déconnecter en haut à droite.  
![Page d'authentification](https://github.com/user-attachments/assets/e6c9c820-68e2-4db6-a8ac-20369e4d7587)
### Page de gestion des formations
#### Page d'affichage des formations existantes
Cette page liste les formations visibles sur la partie front-office.  
Le fonctionnement de cette page est similaire à la page formations sur la partie front-office, la différence majeure est la dernière colonne "actions" qui permet à un administrateur d'ajouter, modifier ou supprimer une formation.  
![Gestion des formations](https://github.com/user-attachments/assets/4f75a707-d684-4902-90d2-12e6c3ca728a)
#### Page d'ajout / modification d'une formation
Cette page comporte un formulaire pour ajouter ou modifier une formation selon le bouton cliqué.  
Il est possible de saisir ces informations sur une formation :
- Le titre
- La description
- La date de publication
- L'identifiant de la vidéo (identifiant de l'url YouTube
- Les catégories
- La playlist  

La page comporte aussi un bouton valider et annuler pour valider ou annuler l'opération.
![Edition d'une formation](https://github.com/user-attachments/assets/240c6723-fa57-43f8-a95d-d7839fa0cbfa)
### Page de gestion des playlists
#### Page d'affichage des playlists existantes
Cette page liste les playlists visibles sur la partie front-office.  
Le fonctionnement de cette page est similaire à la page playlists sur la partie front-office, la différence majeure est la dernière colonne "actions" qui permet à un administrateur d'ajouter, modifier ou supprimer une playlist.  
Afin de supprimer une playlist, il faut s'assurer que celle-ci soit vide, sinon, le bouton "Supprimer" sera désactivé.  
![Gestion des playlists](https://github.com/user-attachments/assets/a291a2e3-cfb4-4d37-86cc-558c650f5241)
#### Page d'ajout / modification d'une playlist
Cette page comporte un formulaire pour ajouter ou modifier une playlist selon le bouton cliqué.  
Il est possible de saisir ces informations sur une playlist :
- Le nom
- La description  

Si c'est une modification d'une playlist, alors la liste de toutes ses formations s'affiche en dessous (il n'est pas possible de les modifier d'ici, il faut pour cela se rendre sur la page d'administration des formations).  
La page comporte aussi un bouton valider et annuler pour valider ou annuler l'opération.
![Edition d'une playlist](https://github.com/user-attachments/assets/a50b2122-e291-47b9-b073-227f0b85bf66)
### Page de gestion des catégories
Cette page liste les catégories possibles pour une formation.  
Le fonctionnement de cette page est similaire aux pages d'administration des formations et playlists, la différence majeure est qu'une catégorie ne peut pas être modifiée, il est seulement possible d'ajouter (via un mini formulaire) et de supprimer une catégorie.  
Cela implique que pour modifier le nom d'une catégorie, il faut supprimer la catégorie et en créer une avec un nom différent.  
![Gestion des catégories](https://github.com/user-attachments/assets/d26c30ba-ca33-4b08-9288-aae7d6a1c08c)
## La base de données
Les informations sur la base de données utilisée par le site internet sont disponible sur [le dépôt GitHub d'origine](https://github.com/cned-slam/mediatekformation?tab=readme-ov-file#la-base-de-donn%C3%A9es)
## Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Clone le code source de ce dépôt dans `www` de WampServer (ou dossier équivalent) puis renommer le dossier en `mediatekformation`.<br>
- Ouvrir une fenêtre de commandes, se positionner dans le dossier du projet et taper `composer install` pour reconstituer le dossier `vendor`.<br>
- Dans phpMyAdmin, se connecter à MySQL en root et créer la base de données nommée `mediatekformation` avec l'encodage `utf8mb4_unicode_ci`.<br>
- Récupérer le fichier `mediatekformation.sql` en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier `.env` en racine du projet).<br>
- Le site devrait maintenant être accessible depuis [localhost/mediatekformation/public/index.php](http://localhost/mediatekformation/public/index.php) (le login / pwd du compte pour la partie back-office par défaut est `admin` / `admin`<br>
