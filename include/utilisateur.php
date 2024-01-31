<?php

class User
{
  public $Pseudo;
  public $id;
  public $mail;
  public $password;
  public $attribut_complementaire;



  function modifier($p, $m, $password)
  {
    $this->Pseudo = $p;
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
    if (isset($_POST['Pseudo'])) {
      $this->Pseudo = $_POST['Pseudo'];
    } else {
      $this->Pseudo = '';
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
    $sql = 'select * from utilisateur where id = :valeur';

    // connexion à la base de données
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // on lie le paramètre :valeur à la variable $id reçue
    $query->bindValue(':valeur', $id, PDO::PARAM_INT);

    // exécution de la requête
    $query->execute();

    // récupération de l'unique ligne
    $objet = $query->fetchObject('user');

    // retourne l'objet contenant résultat
    return $objet;
  }

  static function readAll()
  {
    // définition de la requête SQL
    $sql = 'SELECT * FROM utilisateur';

    // connexion
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // exécution de la requête
    $query->execute();

    // récupération de toutes les lignes sous forme d'objets
    $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'User');

    // retourne le tableau d'objets
    return $tableau;
  }

  function create()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['Pseudo']) && !empty($_POST['mail']) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) && !empty($_POST['Mot_de_passe'])) {

        // construction de la requête :balise, :contenu sont les valeurs à insérées

        $sql = 'INSERT INTO utilisateur (Pseudo, mail, Mot_de_passe) VALUES (:Pseudo, :mail, :Mot_de_passe);';

        // connexion à la base de données
        $pdo = connexion();

        // préparation de la requête
        $query = $pdo->prepare($sql);

        // on donne une valeur aux paramètres à partir des attributs de l'objet courant
        $hashed_password = password_hash($_POST['Mot_de_passe'], PASSWORD_DEFAULT);

        $query->bindValue(':Pseudo', $this->Pseudo, PDO::PARAM_STR);
        $query->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $query->bindValue(':Mot_de_passe', $hashed_password, PDO::PARAM_STR);

        // exécution de la requête
        $query->execute();

        // on récupère la clé de l'element inséré
        $this->id = $pdo->lastInsertId();
      }
    }
  }
  function update()
  {
    // construction de la requête :nom, :contenu sont les valeurs à insérées
    $sql = 'UPDATE utilisateur SET Pseudo = :Pseudo , mail = :mail WHERE id = :id';

    // connexion à la base de données
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // on donne une valeur aux paramètres à partir des attributs de l'objet courant
    $query->bindValue(':Pseudo', $this->Pseudo, PDO::PARAM_STR);
    $query->bindValue(':mail', $this->mail, PDO::PARAM_STR);


    // exécution de la requête
    $query->execute();


    if ($query->errorCode() !== '00000') {
      print_r($query->errorInfo());
      die();
    }
  }


  function conn(){
    // Vérification de la soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérification des champs du formulaire
        if (isset($_POST['Pseudo']) && isset($_POST['Mot_de_passe'])) {
            // Connexion à la base de données


            $pdo = connexion();

            // Requête pour vérifier les informations de connexion dans la base de données
            $pseudo = $_POST['Pseudo'];
            $password = $_POST['Mot_de_passe'];

            $sql = 'SELECT * FROM utilisateur WHERE Pseudo = :Pseudo';
            $query = $pdo->prepare($sql);
            $query->bindParam(':Pseudo', $pseudo);
            $query->execute();

            $user = $query->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe
            if ($user && password_verify($password, $user['Mot_de_passe'])) {
                // Authentification réussie, enregistrement des informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                // $_SESSION['idmusicien'] = $user['idmusicien'];
                $_SESSION['Pseudo'] = $user['Pseudo'];



                // Redirection vers la page de bienvenue Twig après connexion
                header('Location: ./index_utilisateur.php');
                exit();
            } else {
                // Identifiants invalides, redirection vers la page de connexion avec un message d'erreur
                $_SESSION['erreur'] = 'Pseudo ou mot de passe incorrect.';
                header('Location: connexion_utilisateur.twig');
                exit();
            }
        }}
    }

    function deco(){
      session_unset();

      session_destroy();

      header("Location: index.php");

    }

    
}
