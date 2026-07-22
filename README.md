# GesDette — Gestion des dettes clients

Application web en PHP orienté objet (sans framework) permettant à une entreprise de gérer ses clients et le suivi de leurs dettes. Un espace **admin** permet de gérer la liste des clients, et un espace **client** permet à chaque client de consulter sa propre fiche et ses dettes.

## Sommaire

- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Architecture](#architecture)
- [Arborescence du projet](#arborescence-du-projet)
- [Installation](#installation)
- [Comptes de test](#comptes-de-test)
- [Routes disponibles](#routes-disponibles)
- [Points notables](#points-notables)

## Fonctionnalités

**Espace admin** (rôle `admin`)
- Connexion sécurisée par session
- Liste des clients paginée (3 par page), avec recherche par nom et filtre par état
- Ajout d'un client avec upload de photo de profil (JPG/PNG/WEBP/GIF, 2 Mo max)
- Fiche détaillée d'un client : informations + liste de ses dettes
- Validation complète des formulaires côté serveur (champs obligatoires, format email, longueur du mot de passe, téléphone, unicité de l'email, etc.) avec messages d'erreur affichés sous chaque champ

**Espace client** (rôle `client`)
- Connexion sécurisée par session
- Consultation de sa propre fiche et de ses dettes (numéro, montant, date, état)

> **Note** : les actions "Modifier" et "Supprimer" un client sont présentes dans le code (contrôleur, modèle, vue) mais désactivées pour le moment (routes et liens commentés), sur demande du professeur — seule l'action "Voir fiche" est disponible dans un premier temps.

## Stack technique

- **PHP** ( mini-MVC )
- **PostgreSQL** comme SGBD
- **PDO** avec requêtes préparées pour l'accès aux données
- HTML / CSS "vanilla" pour les vues , icônes [Font Awesome](https://fontawesome.com/)

## Architecture

Le projet suit un pattern **MVC** simplifié avec un **front controller** (`public/index.php`) qui route chaque requête vers un contrôleur :

```
Requête HTTP
   -> public/index.php (front controller + autoloader + routeur)
      -> routes/web.php (table de routage : URL -> [Contrôleur, méthode])
         -> app/Controllers/*Controller.php (logique métier, appelle un modèle, choisit une vue)
            -> app/Models/*Model.php (requêtes PDO vers PostgreSQL, hérite de App\Core\Model)
            -> views/**/*.php (affichage HTML, reçoit les données du contrôleur)
```

Classes du socle (`app/Core`) :
- `Database` — connexion PDO à PostgreSQL en singleton
- `Model` — classe de base (méthodes `all`, `find`, `delete` génériques)
- `Controller` — classe de base (rendu de vue, redirection, vérification d'authentification/rôle via `requireAuth`)

Le fichier `helpers/validator.php` fournit une classe `Validator` (API fluide) réutilisée par les formulaires pour valider les champs (`required`, `email`, `minLength`, `phone`, `in`, `image`, ...).

## Arborescence du projet

```
app/
  Controllers/
    UtilisateurController.php   Auth, CRUD clients, fiche client/profil
    DetteController.php
  Core/
    Controller.php              Classe mère des contrôleurs
    Database.php                Connexion PDO (singleton)
    Model.php                   Classe mère des modèles
  Models/
    UtilisateurModel.php        Requêtes SQL sur la table utilisateur
    DetteModel.php               Requêtes SQL sur la table dette
config/
  database.php                  Paramètres de connexion PostgreSQL
helpers/
  validator.php                 Classe Validator (validation de formulaires)
public/
  index.php                     Front controller + autoloader + routeur
  .htaccess                     Réécriture d'URL (Apache)
  css/app.css                   Feuille de style de l'application
  uploads/clients/              Photos de profil des clients (générées à l'usage)
routes/
  web.php                       Table de routage (URL -> Contrôleur::méthode)
views/
  admin/                        Liste, création, édition, fiche client (espace admin)
  auth/                         Page de connexion
  client/                       Tableau de bord du client connecté
  partials/                     Sidebar / topbar réutilisées par les pages connectées
database.sql                    Script de création de la base + jeu de données de test
```

## Installation

### Prérequis

- PHP 8+ avec l'extension `pdo_pgsql` activée
- PostgreSQL (local ou distant)
- Un serveur web (Apache avec `mod_rewrite`) ou simplement le serveur intégré de PHP

### Étapes

1. **Cloner le dépôt** et se placer à la racine du projet.

2. **Créer la base de données** puis importer le script SQL :
   ```bash
   createdb gestion_de_dette
   psql -d gestion_de_dette -f database.sql
   ```
   Le script crée les tables `utilisateur` et `dette`, ainsi qu'un jeu de données de test (voir [Comptes de test](#comptes-de-test)).

3. **Configurer la connexion** dans `config/database.php` (hôte, port, nom de la base, identifiants).

4. **Lancer le serveur** :
   - Avec le serveur intégré de PHP (le plus simple pour tester en local) :
     ```bash
     php -S 127.0.0.1:8000 -t public
     ```
     puis ouvrir `http://127.0.0.1:8000`
   - Ou avec Apache/XAMPP/WAMP : pointer le vhost / dossier `htdocs` vers `public/` (le `.htaccess` gère déjà la réécriture d'URL).

5. Le dossier `public/uploads/clients/` doit être accessible en écriture par le serveur web (il est créé automatiquement au premier upload si besoin).

## Comptes de test

Fournis par `database.sql` (mots de passe en clair — **projet pédagogique local uniquement**) :

| Rôle   | Email                     | Mot de passe |
|--------|---------------------------|--------------|
| admin  | `awa.sow@mail.sn`         | `awa1234`    |
| client | `ben.thiam@mail.sn`       | `ben1234`    |
| client | `penda.diagne@mail.sn`    | `penda1234`  |

## Routes disponibles

| Méthode | URL                  | Contrôleur / méthode                  | Accès  |
|---------|----------------------|----------------------------------------|--------|
| GET     | `/`, `/login`        | `UtilisateurController::login`         | public |
| POST    | `/authenticate`      | `UtilisateurController::authenticate`  | public |
| GET     | `/logout`            | `UtilisateurController::logout`        | connecté |
| GET     | `/clients`           | `UtilisateurController::index`         | admin |
| GET     | `/clients/create`    | `UtilisateurController::create`        | admin |
| POST    | `/clients/store`     | `UtilisateurController::store`         | admin |
| GET     | `/clients/show`      | `UtilisateurController::show`          | admin |
| GET     | `/profil`            | `UtilisateurController::profil`        | client |

*(`/clients/edit`, `/clients/update`, `/clients/delete` existent dans le code mais sont commentées dans `routes/web.php`.)*

## Points notables

- **Sécurité** : requêtes SQL systématiquement préparées (PDO), sortie HTML échappée (`htmlspecialchars`), upload d'image validé par type MIME réel (et non par l'extension du fichier envoyé) avec nom de fichier régénéré côté serveur.
- **Limite connue** : les mots de passe sont stockés et comparés en clair dans `UtilisateurModel::verifyLogin` — acceptable uniquement pour un projet de démonstration local, à ne jamais faire en production (utiliser `password_hash` / `password_verify`).
- **Design** : l'interface (sidebar, palette de couleurs, cartes, tableaux) s'inspire de la charte graphique d'un projet JS réalisé en parallèle durant le semestre.
