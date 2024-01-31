<?php

session_start();

include('include/twig.php');
$twig = init_twig();

include('include/connexion.php');
include('include/utilisateur.php');
include('include/partition.php');

// modification du fichier controleur.php

// récupération de la variable page sur l'URL
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = '';
}

// récupération de la variable action sur l'URL
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'read';
}

// récupération de l'id s'il existe (par convention la clé 0 correspond à un id inexistant)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $id = 0;
}

// test des différents choix
switch ($page) {
  case 'utilisateur':
      switch ($action) {
          case 'read':
              if ($id > 0) {
                  $modele = 'article_utilisateur.twig';
                  $data = [
                      'user' => User::readOne($id),
                      'Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null
                  ];
              } else {
                  $modele = 'article_utilisateur.twig';
                  $data = [
                      'users' => User::readAll(),
                      'Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null
                  ];
              }
              break;

          case 'afficheform':
              $modele = 'ajout_utilisateur.twig';
              $data = ['Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null];
              break;

          case 'edit':
              $modele = 'editer_utilisateur.twig';
              $data = [
                  'user' => User::readOne($id),
                  'Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null
              ];
              break;

          case 'update':
              $user = new User();
              $user->chargePOST();
              $user->update();
              $modele = 'editer_utilisateur.twig';
              $data = ['Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null];
              header("Location: profil_utilisateur.twig");
              exit(); // Ajout de exit() après la redirection
              break;

          default:
              // Si aucun cas ne correspond, définir des valeurs par défaut
              $modele = 'accueil.twig';
              $data = ['Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null];
      }
      break;

  case 'partition':
      switch ($action) {
          case 'read':
              if ($id > 0) {
                  $modele = 'article.twig';
                  $data = [
                      'partition' => Partition::readOne($id),
                      'Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null
                  ];
              } else {
                  $modele = 'article.twig';
                  $data = [
                      'partitions' => Partition::readAll($id),
                      'Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null
                  ];
              }
              break;

          case 'afficheform':
              $modele = 'ajout-partition.twig';
              $data = [
                  'Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null,
                  'idmusicien' => isset($_SESSION['id_musicien']) ? $_SESSION['id_musicien'] : null
              ];
              break;

          case 'create':
              $user = new Partition();
              $user->chargePOST();
              $user->create();
              $data = ['idmusicien' => isset($_SESSION['id_musicien']) ? $_SESSION['id_musicien'] : null];
              header("Location: index_utilisateur.php");
              exit(); // Ajout de exit() après la redirection
              break;

          default:
              // Si aucun cas ne correspond, définir des valeurs par défaut
              $modele = 'accueil.twig';
              $data = ['Pseudo' => isset($_SESSION['Pseudo']) ? $_SESSION['Pseudo'] : null];
      }
      break;

  default:
      // Cas par défaut pour le switch $page
      // Si aucun cas ne correspond, définir des valeurs par défaut
      $modele = 'accueil.twig';
      $data = [];
      break;
}

echo $twig->render($modele, $data);


?>
