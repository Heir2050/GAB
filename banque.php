<?php
include "header.php";
include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}
?>

<?php

$errors = []; // Pour stocker les erreurs

if (isset($_POST['ajouter'])) {
    $banque = $_POST['banque'];

    if (empty($banque)) {
        $errors['banque'] = "Le nom de la banque est obligatoire.";
    } else {
        // Vérification si le nom de la banque existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM banque WHERE nom_banque = :banque");
        $query->bindParam(':banque', $banque, PDO::PARAM_STR);
        $query->execute();
        $emailExists = $query->fetchColumn();

        if ($emailExists > 0) {
            $errors['banque'] = "Cet nom existe déjà.";
        }
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO banque (nom_banque) VALUES (:banque)");
        $insert->bindParam(':banque', $banque, PDO::PARAM_STR);

        $insert->execute();

        header("location:a_banque.php");
    }
}


    # Affichage des donné
    $sql = "SELECT * FROM banque;";
    $statement = $bd->query($sql);  // Executer la requette sql

    // Avoire toute les banques
    $banques = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<section>
    <h1 class="titre">Banque</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <div class="hed_list">
                    <h3 class="title">Ajouter un guichet</h3>
                    <a href="a_banque.php" class="btn">Afficher la liste</a>
                </div>
                <div class="column">
                    <div class="input-box">
                        <span>Nom de la banque :</span>
                        <input type="text" name="banque" placeholder="Ajouter une banque">
                        <small class="message_text"><?= isset($errors['banque']) ? $errors['banque'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="ajouter">Ajouter une banque</button>
            </form>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>