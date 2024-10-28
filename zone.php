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
    $nom = $_POST['nom'];
    $commune = $_POST['commune'];

    if (empty($nom)) {
        $errors['nom'] = "Le nom de la nom est obligatoire.";
    } else {
        // Vérification si le nom de la nom existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM zone WHERE nom_zone = :nom");
        $query->bindParam(':nom', $nom_zone, PDO::PARAM_STR);
        $query->execute();
        $nameExists = $query->fetchColumn();

        if ($nameExists > 0) {
            $errors['nom'] = "Cet nom existe déjà.";
        }
    }

    if (empty($commune)) {
        $errors['commune'] = "Le nom de la commune est obligatoire.";
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO zone (nom_zone, id_commune) VALUES (:nom, :commune)");
        $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
        $insert->bindParam(':commune', $commune, PDO::PARAM_INT);

        $insert->execute();

        header("location:zone.php");
    }
}


# Affiché de tous les Banque
$sql = "SELECT * FROM commune";
$statement = $bd->query($sql);

$communes = $statement->fetchAll(PDO::FETCH_ASSOC);


# Affiché tous les Guichets automatique
$sql = "SELECT * FROM zone as zc join commune as bq on zc.id_commune = bq.id_commune order by id_zone DESC;";
$statement = $bd->query($sql);  // Executer la requette sql

// Recuperer tous les banques
$zones = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <h1 class="titre">Zone</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <!-- <strong></strong> -->
                <h3 class="title">Ajouter une Zone</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Nom de la Zone :</span>
                        <input type="text" name="nom" placeholder="Ajouter une zone">
                        <small class="message_text"><?= isset($errors['nom']) ? $errors['nom'] : '' ?></small>
                    </div>
                </div>
                <div class="input-box">
                        <span>Commune</span>
                        <select name="commune">
                            <option default></option>
                            <?php foreach($communes as $commune): ?>
                                <option value="<?= htmlspecialchars($commune['id_commune']) ?>"><?= htmlspecialchars($commune['nom_commune']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="message_text"><?= isset($errors['commune']) ? $errors['commune'] : '' ?></small>
                    </div>
                <button type="submit" class="btn" name="ajouter">Ajouter une zone</button>
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
                    <th>Commune</th>
                    <th>Zone</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($zones as $zone): ?>
                    <tr>
                        <td><?= htmlspecialchars($zone['id_zone']) ?></td>
                        <td><?= htmlspecialchars($zone['nom_commune']) ?></td>
                        <td><?= htmlspecialchars($zone['nom_zone']) ?></td>
                        <td class="ds">
                            <a href="up_zone.php?update=<?= $zone["id_zone"]; ?>" class="bts warning">Modifier</a>
                            <a href="zone.php?delete=<?= $zone["id_zone"]; ?>" class="bts danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>


<?php include "footer.php"; ?>