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
    $nom = $_POST['nom'];

    if (empty($nom)) {
        $errors['nom'] = "Le nom de la province est obligatoire.";
    } else {
        // Vérification si le nom de la nom existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM province WHERE nom_province = :nom");
        $query->bindParam(':nom', $nom, PDO::PARAM_STR);
        $query->execute();
        $nomExists = $query->fetchColumn();

        if ($nomExists > 0) {
            $errors['nom'] = "Cet nom existe déjà.";
        }
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO province (nom_province) VALUES (:nom)");
        $insert->bindParam(':nom', $nom, PDO::PARAM_STR);

        $insert->execute();

        header("location:province.php");
    }
}

    # Affichage des donné

    $sql = "SELECT * FROM province;";
    $statement = $bd->query($sql);  // Executer la requette sql

    // Avoire toute les banques
    $provinces = $statement->fetchAll(PDO::FETCH_ASSOC);


    #Suppression des données
    $message = "";

    if (isset($_GET['delete'])) {
        $delete = $bd->query("DELETE FROM province WHERE id_province=".$_GET['delete']);

        header("location:province.php");

        $message = "Supprimé avec succès";
    }
?>

<section>
    <h1 class="titre">Province</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <div class="hed_list">
                    <h3 class="title">Ajouter un guichet</h3>
                    <a href="province.php" class="btn">Afficher la liste</a>
                </div>
                <div class="column">
                    <div class="input-box">
                        <span>Nom de la province :</span>
                        <input type="text" name="nom" placeholder="Ajouter une province">
                        <small class="message_text"><?= isset($errors['nom']) ? $errors['nom'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="ajouter">Ajouter une province</button>
            </form>
        </div>
    </div>
</section>

<section>
    <div class="cont">
        <?php if ($statement): ?>
            <table style="width: 40%;">
                <tr>
                    <th>ID</th>
                    <th>Nom de la province</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($provinces as $province): ?>
                    <tr>
                        <td><?= htmlspecialchars($province['id_province']) ?></td>
                        <td><?= htmlspecialchars($province['nom_province']) ?></td>
                        <td class="ds">
                            <a href="up_provinces.php?update=<?= $province["id_province"]; ?>" class="bts warning">Modifier</a>
                            <a href="province.php?delete=<?= $province["id_province"]; ?>" class="bts danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>


<?php include "footer.php"; ?>