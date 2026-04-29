<?php
require_once __DIR__ . "/../config/config.php";

function lireUtilisateurs()
{
    if (!file_exists(UTILISATEURS_FILE)) {
        return [];
    }
    $data = file_get_contents(UTILISATEURS_FILE);
    return json_decode($data, true) ?: [];
}

function sauvegarderUtilisateurs($utilisateurs)
{
    file_put_contents(UTILISATEURS_FILE, json_encode($utilisateurs, JSON_PRETTY_PRINT));
}

function ajouterUtilisateur($identifiant, $nom_complet, $mot_de_passe, $role)
{
    $utilisateurs = lireUtilisateurs();
    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur['identifiant'] === $identifiant) {
            return false; // L'identifiant existe déjà
        }
    }
    $nouveau_utilisateur = [
        'identifiant' => $identifiant,
        'nom_complet' => $nom_complet,
        'mot_de_passe' => $mot_de_passe, // Mot de passe en clair
        'role' => $role,
    ];
    $utilisateurs[] = $nouveau_utilisateur;
    sauvegarderUtilisateurs($utilisateurs);
    return true;
}

function verifierUtilisateur($identifiant, $mot_de_passe)
{
    $utilisateurs = lireUtilisateurs();
    foreach ($utilisateurs as $utilisateur) {
        if (
            $utilisateur["identifiant"] === $identifiant &&
            $mot_de_passe === $utilisateur["mot_de_passe"] // Comparaison du mot de passe en clair
        ) {
            return $utilisateur;
        }
    }
    return null;
}
