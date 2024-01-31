<?php
// Inclure le fichier de connexion à la base de données
include("connexion.php");

// Vérifier si l'ID de la partition est spécifié dans l'URL
if (isset($_GET["i_partition"])) {
    $id = mysqli_real_escape_string($connexion, $_GET["id"]);

    // Récupérer le PDF depuis la base de données
    $sql = "SELECT document FROM `partition` WHERE id = $id";
    $result = $connexion->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        // Définir les en-têtes pour indiquer que c'est un fichier PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $row["title"] . '.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');

        // Afficher le contenu du PDF
        echo $row["document"];
    } else {
        echo "PDF introuvable.";
    }
} else {
    echo "Identifiant de la partition non spécifié.";
}
?>