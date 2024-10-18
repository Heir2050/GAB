<?php
    include "header.php"; 
    include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}

    # Affiché de tous les quartier
    $sql = "SELECT * FROM zone";
    $statement = $bd->query($sql);

    // Recuperer tous les quartiers
    $quartiers = $statement->fetchAll(PDO::FETCH_ASSOC);


# Get the zone element for doing an update
// Récupérer l'ID du zone depuis l'URL
$nom_zone = '';
$id_zone = '';
$nom_quartier = '';
$id_quartier  = 0;
if (isset($_GET['update'])) {
    $id_zone = $_GET['update'];

    // Requête pour récupérer les détails du zone spécifique
    $sql = "SELECT * FROM zone as gc
            JOIN quartier as bq ON gc.id_quartier = bq.id_quartier
            WHERE gc.id_zone = :id_zone";
    
    // Préparer et exécuter la requête
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':id_zone', $id_zone, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer les résultats
    $zone = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le zone existe
    if ($zone) {
        // Pré-remplir les champs avec les valeurs du zone
        $id_zone = $zone['id_zone'];
        $id_quartier = $zone['id_quartier'];
        $nom_quartier = $zone['nom_quartier'];
        $nom_zone = $zone['nom_zone'];
    } else {
        echo "zone non trouvé";
    }
}


# Modification des éléments
if (isset($_POST['update'])) {
    $id_zone = $_POST['id_zone'];
    $nom_zone = $_POST['nom_zone'];
    $quartier = $_POST['quartier'];

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $update = $bd->prepare("UPDATE zone SET nom_zone = :nom_zone, id_quartier = :quartier WHERE id_zone = :id_zone");
    $update->bindParam(':id_zone', $id_zone, PDO::PARAM_INT);
    $update->bindParam(':nom_zone', $nom_zone, PDO::PARAM_STR);
    $update->bindParam(':quartier', $quartier, PDO::PARAM_STR);

    $update->execute();

    header("location:zone.php");
}
?>

<section>
    <h1 class="titre">zones automatique</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="post" style="width: 500px;">
            <input type="hidden" name="id_zone" value="<?= htmlspecialchars($id_zone ?? '') ?>">
                <!-- <div class="message"></div> -->
                <h3 class="title">Ajouter un zone</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Nom du zone:</span>
                        <input type="text" name="nom_zone" value="<?= htmlspecialchars($nom_zone ?? '') ?>" placeholder="Ajouter le nom du zone">
                    </div>
                    <div class="input-box">
                        <span>quartier</span>
                        <select name="quartier">
                            <?php if(!empty($nom_quartier)): ?>
                                <option default value="<?= htmlspecialchars($id_quartier ?? '') ?>"><?= htmlspecialchars($nom_quartier ?? '') ?></option>
                                <?php foreach($quartiers as $quartier): ?>
                                    <option value="<?= htmlspecialchars($quartier['id_quartier']) ?>"><?= htmlspecialchars($quartier['nom_quartier']) ?></option>
                                <?php endforeach; ?>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
                <input type="submit" class="btn" name="update" value="Modifier">
            </form>
        </div>
    </div>
</section>


<?php include "footer.php"; ?>