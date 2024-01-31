<?php
session_start();

include('include/twig.php');
$twig = init_twig();

include('include/connexion.php');

include('include/utilisateur.php');
include('include/musicien.php');
include('include/partition.php');

// modification du fichier controleur.php

// récupération de la variable page sur l'URL
if (isset($_GET['page'])) $page = $_GET['page'];
else $page = '';

// récupération de la variable action sur l'URL
if (isset($_GET['action'])) $action = $_GET['action'];
else $action = 'read';

// récupération de l'id s'il existe (par convention la clé 0 correspond à un id inexistant)
if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = 0;


// test des différents choix
switch ($page) {
    case 'tous':
        switch ($action) {
            case 'read':
                if ($id > 0) {
                    $modele = 'accueil.twig';
                    $data = ['user' => User::readOne($id)];
                } else {
                    $modele = 'accueil.twig';
                    $data = ['users' => User::readAll()];
                }
                break;
            }
            case 'afficheProfils':
                    $modele = 'profil.twig';
                    $data = [];
                    break;
                    case 'readuser':
                        if ($id > 0) {
                            $modele = 'accueil_utilisateur.twig';
                            $data = ['user' => User::readOne($id)];
                        } else {
                            $modele = 'accueil_utilisateur.twig';
                            $data = ['users' => User::readAll()];
                        }
                        break;
      
    default:
        // Si aucun cas ne correspond, définir des valeurs par défaut
        $modele = 'accueil.twig';
        $data = [''];
        break;
}
echo $twig->render($modele, $data);
