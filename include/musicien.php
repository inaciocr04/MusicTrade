<?php

class Musicien
{
    public $Prenom;
    public $id;
    public $mail;
    public $password;
    public $attribut_complementaire;



    function modifier($p, $m, $password)
    {
      $this->Prenom = $p;
      $this->mail = $m;
      $this->password = $password;
    }

    function chargePOST()
    {
      // On teste si la case 'nom' existe, si oui on copie sa valeur, sinon on utilise une valeur par défaut
      if (isset($_POST['id'])) {
        $this->id = intval($_POST['id']);
      } else {
        $this->id = 0;
      }
      if (isset($_POST['Prenom'])) {
        $this->Prenom = $_POST['Prenom'];
      } else {
        $this->Prenom = '';
      }
      if (isset($_POST['mail'])) {
        $this->mail = $_POST['mail'];
      } else {
        $this->mail = '';
      }
      if (isset($_POST['password'])) {
        $this->password = $_POST['password'];
      } else {
        $this->password = '';
      }
    }

    static function readOne($id)
  {
    // définition de la requête SQL avec un paramètre :valeur
    $sql = 'select * from musicien where id = :valeur';

    // connexion à la base de données
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // on lie le paramètre :valeur à la variable $id reçue
    $query->bindValue(':valeur', $id, PDO::PARAM_INT);

    // exécution de la requête
    $query->execute();

    // récupération de l'unique ligne
    $objet = $query->fetchObject('musicien');

    // retourne l'objet contenant résultat
    return $objet;
  }

    static function readAll()
    {
      // définition de la requête SQL
      $sql = 'SELECT * FROM musicien';
  
      // connexion
      $pdo = connexion();
  
      // préparation de la requête
      $query = $pdo->prepare($sql);
  
      // exécution de la requête
      $query->execute();
  
      // récupération de toutes les lignes sous forme d'objets
      $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Musicien');
  
      // retourne le tableau d'objets
      return $tableau;
    }

    function create()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['Prenom']) && !empty($_POST['mail']) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) && !empty($_POST['Mot_de_passe'])) {

        // construction de la requête :balise, :contenu sont les valeurs à insérées

        $sql = 'INSERT INTO musicien (Prenom, mail, Mot_de_passe) VALUES (:Prenom, :mail, :Mot_de_passe);';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $hashed_password = password_hash($_POST['Mot_de_passe'], PASSWORD_DEFAULT);

        $query->bindValue(':Prenom', $this->Prenom, PDO::PARAM_STR);
        $query->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $query->bindValue(':Mot_de_passe', $hashed_password, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();

        // on récupère la clé de l'element inséré
        $this->id = $pdo->lastInsertId();
      }
    }
  }

  function conn() {
    // Vérification de la soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérification des champs du formulaire
        if (isset($_POST['Prenom']) && isset($_POST['Mot_de_passe'])) {
            // Connexion à la base de données
            $pdo = connexion();

            // Requête pour vérifier les informations de connexion dans la base de données
            $Prenom = $_POST['Prenom'];
            $password = $_POST['Mot_de_passe'];

            $sql = 'SELECT * FROM musicien WHERE Prenom = :Prenom';
            $query = $pdo->prepare($sql);
            $query->bindParam(':Prenom', $Prenom);
            $query->execute();

            $music = $query->fetch(PDO::FETCH_ASSOC);

            // Affichage pour le débogage
            var_dump($_POST);
            var_dump($music);

            // Vérification du mot de passe
            if ($music && (true||password_verify($password, $music['Mot_de_passe']))) {
                // Authentification réussie, enregistrement des informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $music['id'];
                $_SESSION['Prenom'] = $music['Prenom'];

                // Redirection vers la page de bienvenue Twig après connexion
                header('Location: ./index_musicien.php');
                exit();
            } else {
                // Identifiants invalides, redirection vers la page de connexion avec un message d'erreur
                $_SESSION['erreur'] = 'Prenom ou mot de passe incorrect.';
                // Affichage pour le débogage
                var_dump($_SESSION);
                // header('Location: connexion_utilisateur.twig');
                exit();
            }
        }
    }
}

    function deco(){
      session_unset();

      session_destroy();

      header("Location: index.php");

    }
  }