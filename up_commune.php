<?php
    include "header.php"; 
    include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}

    # Affiché de tous les province
    $sql = "SELECT * FROM province";
    $statement = $bd->query($sql);

    // Recuperer tous les provinces
    $provinces = $statement->fetchAll(PDO::FETCH_ASSOC);


# Get the commune element for doing an update
// Récupérer l'ID du commune depuis l'URL
$nom_commune = '';
$id_commune = '';
$nom_province = '';
$id_province  = 0;
if (isset($_GET['update'])) {
    $id_commune = $_GET['update'];

    // Requête pour récupérer les détails du commune spécifique
    $sql = "SELECT * FROM commune as gc
            JOIN province as bq ON gc.id_province = bq.id_province
            WHERE gc.id_commune = :id_commune";
    
    // Préparer et exécuter la requête
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':id_commune', $id_commune, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer les résultats
    $commune = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le commune existe
    if ($commune) {
        // Pré-remplir les champs avec les valeurs du commune
        $id_commune = $commune['id_commune'];
        $id_province = $commune['id_province'];
        $nom_province = $commune['nom_province'];
        $nom_commune = $commune['nom_commune'];
    } else {
        echo "commune non trouvé";
    }
}



# Modification des éléments
if (isset($_POST['update'])) {
    $id_commune = $_POST['id_commune'];
    $nom_commune = $_POST['nom_commune'];
    $province = $_POST['province'];

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $update = $bd->prepare("UPDATE commune SET nom_commune = :nom_commune, id_province = :province WHERE id_commune = :id_commune");
    $update->bindParam(':id_commune', $id_commune, PDO::PARAM_INT);
    $update->bindParam(':nom_commune', $nom_commune, PDO::PARAM_STR);
    $update->bindParam(':province', $province, PDO::PARAM_STR);

    $update->execute();

    header("location:commune.php");
}
?>

<section>
    <h1 class="titre">communes automatique</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="post" style="width: 500px;">
            <input type="hidden" name="id_commune" value="<?= htmlspecialchars($id_commune ?? '') ?>">
                <!-- <div class="message"></div> -->
                <h3 class="title">Ajouter un commune</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Nom du commune:</span>
                        <input type="text" name="nom_commune" value="<?= htmlspecialchars($nom_commune ?? '') ?>" placeholder="Ajouter le nom du commune">
                    </div>
                    <div class="input-box">
                        <span>province</span>
                        <select name="province">
                            <?php if(!empty($nom_province)): ?>
                                <option default value="<?= htmlspecialchars($id_province ?? '') ?>"><?= htmlspecialchars($nom_province ?? '') ?></option>
                                <?php foreach($provinces as $province): ?>
                                    <option value="<?= htmlspecialchars($province['id_province']) ?>"><?= htmlspecialchars($province['nom_province']) ?></option>
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