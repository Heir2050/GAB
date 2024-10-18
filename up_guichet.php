<?php
    include "header.php"; 
    include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}

    # Affiché de tous les Banque
    $sql = "SELECT * FROM banque";
    $statement = $bd->query($sql);

    // Recuperer tous les banques
    $banques = $statement->fetchAll(PDO::FETCH_ASSOC);


# Get the guichet element for doing an update
// Récupérer l'ID du guichet depuis l'URL
// Initialiser les variables pour éviter l'erreur 'undefined variable'
$nom_banque = '';
$id_banque = '';
$nom_guichet = '';
$quartier = '';
$id_guichet  = 0;
if (isset($_GET['id_guichet'])) {
    $id_guichet = $_GET['id_guichet'];

    // Requête pour récupérer les détails du guichet spécifique
    $sql = "SELECT * FROM guichet as gc
            JOIN banque as bq ON gc.id_banque = bq.id_banque
            WHERE gc.id_guichet = :id_guichet";
    
    // Préparer et exécuter la requête
    $stmt = $bd->prepare($sql);
    $stmt->bindParam(':id_guichet', $id_guichet, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer les résultats
    $guichet = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le guichet existe
    if ($guichet) {
        // Pré-remplir les champs avec les valeurs du guichet
        $id_guichet = $guichet['id_guichet'];
        $id_banque = $guichet['id_banque'];
        $nom_banque = $guichet['nom_banque'];
        $nom_guichet = $guichet['nom_guichet'];
        $quartier = $guichet['id_quartier'];
    } else {
        echo "Guichet non trouvé";
    }
}



# Modification des éléments
if (isset($_POST['update'])) {
    $id_guichet = $_POST['id_guichet'];
    $nom_guichet = $_POST['nom_guichet'];
    $banque = $_POST['banque'];
    $quartier = $_POST['quartier'];

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $update = $bd->prepare("UPDATE guichet SET nom_guichet = :nom_guichet, id_banque = :banque, status = :status WHERE id_guichet = :id_guichet");
    $update->bindParam(':id_guichet', $id_guichet, PDO::PARAM_INT);
    $update->bindParam(':nom_guichet', $nom_guichet, PDO::PARAM_STR);
    $update->bindParam(':banque', $banque, PDO::PARAM_STR);
    $update->bindParam(':quartier', $quartier, PDO::PARAM_STR);

    $update->execute();

    header("location:index.php");
}
?>

<section>
    <h1 class="titre">Guichets automatique</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="post" style="width: 500px;">
            <input type="hidden" name="id_guichet" value="<?= htmlspecialchars($id_guichet ?? '') ?>">
                <!-- <div class="message"></div> -->
                <h3 class="title">Ajouter un guichet</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Nom du guichet:</span>
                        <input type="text" name="nom_guichet" value="<?= htmlspecialchars($nom_guichet ?? '') ?>" placeholder="Ajouter le nom du guichet">
                    </div>
                    <div class="input-box">
                        <span>Banque</span>
                        <select name="banque">
                            <?php if(!empty($nom_banque)): ?>
                                <option default value="<?= htmlspecialchars($id_banque ?? '') ?>"><?= htmlspecialchars($nom_banque ?? '') ?></option>
                                <?php foreach($banques as $banque): ?>
                                    <option value="<?= htmlspecialchars($banque['id_banque']) ?>"><?= htmlspecialchars($banque['nom_banque']) ?></option>
                                <?php endforeach; ?>
                            <?php endif ?>
                        </select>
                    </div>
                    
                    <div class="input-box">
                        <span>Quartier</span>
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
                <input type="submit" class="btn" name="update" value="Ajouter un guichet">
            </form>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>