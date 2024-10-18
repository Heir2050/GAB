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
    $zone = $_POST['zone'];

    if (empty($nom)) {
        $errors['nom'] = "Le nom de la nom est obligatoire.";
    } else {
        // Vérification si le nom de la nom existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM quartier WHERE nom_quartier = :nom");
        $query->bindParam(':nom', $nom, PDO::PARAM_STR);
        $query->execute();
        $nameExists = $query->fetchColumn();

        if ($nameExists > 0) {
            $errors['nom'] = "Cet nom existe déjà.";
        }
    }

    if (empty($zone)) {
        $errors['zone'] = "Le nom de la zone est obligatoire.";
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO quartier (nom_quartier, id_zone) VALUES (:nom, :zone)");
        $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
        $insert->bindParam(':zone', $zone, PDO::PARAM_INT);

        $insert->execute();

        header("location:quartier.php");
    }
}

    # Affiché tous les Guichets automatique
    $sql = "SELECT * FROM quartier as zc join zone as bq on zc.id_zone = bq.id_zone order by id_quartier DESC;";
    $statement = $bd->query($sql);  // Executer la requette sql

    // Recuperer tous les banques
    $quartiers = $statement->fetchAll(PDO::FETCH_ASSOC);

    # Affiché de tous les Banque
    $sql = "SELECT * FROM zone";
    $statement = $bd->query($sql);

    $zones = $statement->fetchAll(PDO::FETCH_ASSOC);

    
    #Suppression des données
    $message = "";

    if (isset($_GET['delete'])) {
        $delete = $bd->query("DELETE FROM quartier WHERE id_quartier=".$_GET['delete']);

        header("location:quartier.php");

        $message = "Supprimé avec succès";
    }
?>

<section>
    <h1 class="titre">Quartier</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <!-- <strong></strong> -->
                <h3 class="title">Ajouter un quartier</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Nom du quartier :</span>
                        <input type="text" name="nom" placeholder="Ajouter un quartier">
                        <small class="message_text"><?= isset($errors['nom']) ? $errors['nom'] : '' ?></small>
                    </div>
                    <div class="input-box">
                        <span>Choisir la zone</span>
                        <select name="zone">
                            <option default></option>
                            <?php foreach($zones as $zone): ?>
                                <option value="<?= htmlspecialchars($zone['id_zone']) ?>"><?= htmlspecialchars($zone['nom_zone']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="message_text"><?= isset($errors['zone']) ? $errors['zone'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="ajouter">Ajouter un quartier</button>
            </form>
        </div>
    </div>
</section>

<section>
    <div class="cont">
        <?php if ($statement): ?>
            <table style="width: 50%;">
                <tr>
                    <th>ID</th>
                    <th>Nom de la zone</th>
                    <th>Nom du quartier</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($quartiers as $quartier): ?>
                    <tr>
                        <td><?= htmlspecialchars($quartier['id_quartier']) ?></td>
                        <td><?= htmlspecialchars($quartier['nom_zone']) ?></td>
                        <td><?= htmlspecialchars($quartier['nom_quartier']) ?></td>
                        <td class="ds">
                            <a href="up_quartier.php?update=<?= $quartier["id_quartier"]; ?>" class="bts warning">Modifier</a>
                            <a href="quartier.php?delete=<?= $quartier["id_quartier"]; ?>" class="bts danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>


<?php include "footer.php"; ?>