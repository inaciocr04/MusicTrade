<?php

session_start();


include('include/twig.php');
$twig = init_twig();


include('include/connexion.php');
include('include/musicien.php');


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
  case 'musicien':
    switch ($action) {
        case 'read':
            if ($id > 0) {
                $modele = 'profil_musicien.twig';
                $data = ['musicien' => User::readOne($id),
                'Prenom' => isset($_SESSION['Prenom']) ? $_SESSION['Prenom'] : null
              ];
            } else {
                $modele = 'profil_musicien.twig';
                $data = ['musiciens' => User::readAll(),
                'Prenom' => isset($_SESSION['Prenom']) ? $_SESSION['Prenom'] : null
              ];
            }
        break;

        case 'conn':
          $musicien = new Musicien();
          if ($_SERVER['REQUEST_METHOD'] === 'POST'){
          $musicien->conn();
          }
          $modele = 'compte_musicien.twig';
          $data = [
              'Prenom' => isset($_SESSION['Prenom']) ? $_SESSION['Prenom'] : null
          ];
        break;
          case 'deco':
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
              if ($_GET['action'] === 'deconnexion') {
                  $musicien->deco();
              }
              $modele ='index.php';
              $data = [
                'Prenom' => isset($_SESSION['Prenom']) ? $_SESSION['Prenom'] : null
            ];
          }
          break;
    } 
    default:
        // Si aucun cas ne correspond, définir des valeurs par défaut
        $modele = 'accueil.twig';
        $data = ['Prenom' => isset($_SESSION['Prenom']) ? $_SESSION['Prenom'] : null]; 
        break;
}
echo $twig->render($modele, $data);