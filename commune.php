<?php
include "header.php";
include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}


$errors = []; // Pour stocker les erreurs

if (isset($_POST['ajouter'])) {
    $commune = $_POST['commune'];
    $province = $_POST['province'];

    if (empty($commune)) {
        $errors['commune'] = "Le nom de la commune est obligatoire.";
    } else {
        // Vérification si le nom de la commune existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM commune WHERE nom_commune = :commune");
        $query->bindParam(':commune', $commune, PDO::PARAM_STR);
        $query->execute();
        $nameExists = $query->fetchColumn();

        if ($nameExists > 0) {
            $errors['commune'] = "Cet nom existe déjà.";
        }
    }

    if (empty($province)) {
        $errors['province'] = "Le nom de la province est obligatoire.";
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO commune (nom_commune, id_province) VALUES (:commune, :province)");
        $insert->bindParam(':commune', $commune, PDO::PARAM_STR);
        $insert->bindParam(':province', $province, PDO::PARAM_INT);

        $insert->execute();

        header("location:commune.php");
    }
}

    # Affiché de tous les provinces
    $sql = "SELECT * FROM province";
    $statement = $bd->query($sql);

    $provinces = $statement->fetchAll(PDO::FETCH_ASSOC);


    # Affiché tous les Guichets automatique
    $sql = "SELECT * FROM commune as cc join province as bq on cc.id_province = bq.id_province order by id_commune DESC;";
    $statement = $bd->query($sql);  // Executer la requette sql

    $communes = $statement->fetchAll(PDO::FETCH_ASSOC);

    #Suppression des données
    $message = "";

    if (isset($_GET['delete'])) {
        $delete = $bd->query("DELETE FROM commune WHERE id_commune=".$_GET['delete']);

        header("location:commune.php");

        $message = "Supprimé avec succès";
    }
?>

<section>
    <h1 class="titre">Commune</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <h3 class="title">Ajouter une commune</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Nom de la commune :</span>
                        <input type="text" name="commune" placeholder="Ajouter une commune">
                        <small class="message_text"><?= isset($errors['commune']) ? $errors['commune'] : '' ?></small>
                    </div>
                </div>
                <div class="input-box">
                    <span>Province</span>
                    <select name="province">
                        <option default></option>
                        <?php foreach ($provinces as $province): ?>
                            <option value="<?= htmlspecialchars($province['id_province']) ?>"><?= htmlspecialchars($province['nom_province']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="message_text"><?= isset($errors['province']) ? $errors['province'] : '' ?></small>
                </div>
                <button type="submit" class="btn" name="ajouter">Ajouter une commune</button>
            </form>
        </div>
    </div>
</section>

<section>
    <div class="cont">
        <?php if (!empty($communes)): ?>
            <?php if ($statement): ?>
                <table style="width: 40%;">
                    <tr>
                        <th>ID</th>
                        <th>Province</th>
                        <th>Commune</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($communes as $commune): ?>
                        <tr>
                            <td><?= htmlspecialchars($commune['id_commune']) ?></td>
                            <td><?= htmlspecialchars($commune['nom_province']) ?></td>
                            <td><?= htmlspecialchars($commune['nom_commune']) ?></td>
                            <td class="ds">
                                <a href="up_commune.php?update=<?= $commune["id_commune"]; ?>" class="bts warning">Modifier</a>
                                <a href="commune.php?delete=<?= $commune["id_commune"]; ?>" class="bts danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php else: ?>
            <div class="no_data">
                No data found!
            </div>
        <?php endif; ?>
    </div>
</section>


<?php include "footer.php"; ?>