# Facturation Express (Application FAQ)

Bienvenue sur Facturation Express, une application de facturation simple et rapide, conçue pour répondre à vos besoins urgents. Ce projet a été développé en un temps record pour une Foire Aux Questions (FAQ), en mettant l'accent sur la simplicité et l'efficacité.

## Auteurs

-   MOKOLI NGOY Camille
-   MBELE KALENGA Joseph
-   MALEKA LIKEMBO Nicolas


## Fonctionnalités

-   **Tableau de bord interactif :** Visualisez les ventes journalières et l'activité récente.
-   **Gestion des factures :** Créez, consultez et gérez l'historique de toutes vos factures.
-   **Gestion des produits :** Maintenez un catalogue de produits facilement accessible.
-   **Rapports détaillés :** Générez des rapports journaliers et mensuels pour un suivi précis.
-   **Authentification sécurisée :** Système de connexion avec gestion des rôles (Caissier, Manager, Super Administrateur).

## Technologies Utilisées

Ce projet s'appuie sur une pile technologique légère et performante :

-   **Backend :** PHP Natif (sans framework lourd, pour une rapidité d'exécution maximale).
-   **Stockage des données :** Fichiers JSON (produits, factures, utilisateurs) – pas de base de données SQL pour une installation ultra-rapide.
-   **Frontend :**
    -   HTML5
    -   Tailwind CSS v4 (pour un développement CSS rapide et modulaire).
    -   DaisyUI v5 (kit de composants UI pour Tailwind CSS, offrant un design moderne).
-   **Inspiration visuelle :** Un thème graphique amusant inspiré de l'univers d'Animal Crossing, avec une touche de Tom Nook !

## Installation Rapide

1.  **Prérequis :**
    -   Un serveur web avec PHP (par exemple, WampServer, XAMPP, ou équivalent).
2.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/votre-utilisateur/Facturation_TP_PHP.git
    cd Facturation_TP_PHP
    ```
3.  **Installation des dépendances front-end (pour Tailwind CSS et DaisyUI) :**
    ```bash
    npm install
    npm run build:css
    ```
    *(Assurez-vous que Node.js et npm sont installés sur votre machine si vous souhaitez recompiler le CSS.)*
4.  **Déploiement :**
    -   Placez le dossier `Facturation_TP_PHP` dans le répertoire `www` de votre serveur Wamp/XAMPP.
    -   Accédez à l'application via votre navigateur : `http://localhost/Facturation_TP_PHP`.

Et voilà ! L'application devrait être opérationnelle.
