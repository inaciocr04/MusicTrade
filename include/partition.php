<?php
class Partition
{
    public $titre;
    public $description;
    public $id;
    public $image;
    public $document;
    public $id_musicien;
    public $id_admin;
    public $id_categorie;
    public $id_sous_cat;
    public $attribut_complementaire;



    function modifier($t, $d, $i, $doc)
    {
      $this->titre = $t;
      $this->description = $d;
      $this->image = $i;
      $this->document = $doc;
    }

    function chargePOST()
    {
      // On teste si la case 'nom' existe, si oui on copie sa valeur, sinon on utilise une valeur par défaut
      if (isset($_POST['id'])) {
        $this->id = intval($_POST['id']);
      } else {
        $this->id = 0;
      }
      if (isset($_POST['titre'])) {
        $this->titre = $_POST['titre'];
      } else {
        $this->titre = '';
      }
      // Idem pour le prénom
      if (isset($_POST['description'])) {
        $this->description = $_POST['description'];
      } else {
        $this->description = '';
      }
      if (isset($_POST['image'])) {
        $this->image = $_POST['image'];
      } else {
        $this->image = '';
      }
      if (isset($_POST['document'])) {
        $this->document = $_POST['document'];
      } else {
        $this->document = '';
      }

      if (isset($_POST['id_categorie'])) {
        $this->id_categorie = $_POST['id_categorie'];
      } else {
        $this->id_categorie = '0';
      }

    }

    static function readAll($id)
    {
      // définition de la requête SQL
      $sql = 'SELECT titre,description,image,document FROM `partition`';
  
      // connexion
      $pdo = connexion();
  
      // préparation de la requête
      $query = $pdo->prepare($sql);
  
      // exécution de la requête
      $query->execute();
  
      // récupération de toutes les lignes sous forme d'objets
      $query->bindValue(':id', $id, PDO::PARAM_INT);
      $tableau = $query->fetchAll(PDO::FETCH_CLASS, 'Partition');
  
      // retourne le tableau d'objets
      return $tableau;
    }

    static function readOne($id)
    {
      // définition de la requête SQL avec un paramètre :valeur
      $sql = 'select * from `partition` where id = :valeur';
  
      // connexion à la base de données
      $pdo = connexion();
  
      // préparation de la requête
      $query = $pdo->prepare($sql);
  
      // on lie le paramètre :valeur à la variable $id reçue
      $query->bindValue(':valeur', $id, PDO::PARAM_INT);
  
      // exécution de la requête
      $query->execute();
  
      // récupération de l'unique ligne
      $objet = $query->fetchObject('partition');
  
      // retourne l'objet contenant résultat
      return $objet;
    }
    function __construct()
    {
      // conversion d'un attribut
      $this->id = intval($this->id);
  
      // remplace une valeur vide par autre chose
      if (empty($this->titre)) $this->titre = 'inconnu';
  
      // définit un attribut complémentaire (hors base de données)
      $this->attribut_complementaire = 'valeur hors base de données';
    }

    function create()
  {
    // construction de la requête :balise, :contenu sont les valeurs à insérées
    $sql = 'INSERT INTO `partition` (titre, description, image, document, id_musicien) VALUES (:titre, :description, :image, :document, :id_musicien)';

    $idmusicien = $_POST['idmusicien'];

    // connexion à la base de données
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // on donne une valeur aux paramètres à partir des attributs de l'objet courant
    $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
    $query->bindValue(':description', $this->description, PDO::PARAM_STR);
    $query->bindValue(':image', $this->image, PDO::PARAM_STR);
    $query->bindValue(':document', $this->document, PDO::PARAM_STR);
    $query->bindValue(':id_musicien', $this->id_musicien, PDO::PARAM_INT);


    // exécution de la requête
    $query->execute();

    // on récupère la clé de l'element inséré
    $this->id = $pdo->lastInsertId();
  }

  // fonction DELETE
  static function delete($id)
  {
    // construction de la requête :nom, :contenu sont les valeurs à insérées
    $sql = 'DELETE FROM `partition` WHERE id = :id';

    // connexion à la base de données
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // on lie le paramètre :id à la variable $id reçue
    $query->bindValue(':id', $id, PDO::PARAM_INT);

    // exécution de la requête
    $query->execute();
  }

  function update()
  {
    // construction de la requête :nom, :contenu sont les valeurs à insérées
    $sql = 'UPDATE `partition` SET titre = :titre , description = :description , image = :image , document = :document WHERE id = :id ';

    // connexion à la base de données
    $pdo = connexion();

    // préparation de la requête
    $query = $pdo->prepare($sql);

    // on donne une valeur aux paramètres à partir des attributs de l'objet courant
    $query->bindValue(':titre', $this->titre, PDO::PARAM_STR);
    $query->bindValue(':description', $this->description, PDO::PARAM_STR);
    $query->bindValue(':image', $this->image, PDO::PARAM_STR);
    $query->bindValue(':document', $this->document, PDO::PARAM_STR);
    $query->bindValue(':id', $this->id, PDO::PARAM_INT);

    // exécution de la requête
    $query->execute();


    if ($query->errorCode() !== '00000') {
      print_r($query->errorInfo());
      die();
    }

}
}