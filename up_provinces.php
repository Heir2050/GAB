<?php
include "header.php";
include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}

$message;
// Modification des éléments

// Get the contact element for doing an update
$stmt = $bd->query('SELECT * FROM province WHERE id_province ='.$_GET['update']);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);


// Suppression d'un contact
if (isset($_GET['delete'])) {
    // Utilisation de requêtes préparées pour éviter les injections SQL
    $delete = $bd->prepare("DELETE FROM province WHERE id_province = :id_province");
    $delete->bindParam(':id_province', $_GET['delete'], PDO::PARAM_INT);
    $delete->execute();

    header("location:a_province.php");
}

// Modification des éléments
if (isset($_POST['update'])) {
    $id_province = $_POST['id_province'];
    $nom_province = $_POST['nom_province'];

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $update = $bd->prepare("UPDATE province SET nom_province = :nom_province WHERE id_province = :id_province");
    $update->bindParam(':nom_province', $nom_province, PDO::PARAM_STR);
    $update->bindParam(':id_province', $id_province, PDO::PARAM_INT);

    $update->execute();

    header("location:province.php");
}
?>

<section>
    <h1 class="titre">province</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" enctype="multipart/form-data" style="width: 500px;">
                <!-- <strong></strong> -->
                <h3 class="title">Modifier un province</h3>
                <div class="column">
                    <input type="hidden" name="id_province" value="<?php echo $contact['id_province']; ?>">
                    <div class="input-box">
                        <span>Téléphone:</span>
                        <input type="text" name="nom_province" value="<?= $contact['nom_province']; ?>" placeholder="Votre numero de téléphone">
                    </div>
                </div>
                <button type="submit" class="btn" name="update">Modifier un province</button>
            </form>
        </div>
    </div>
</section>



<?php include "footer.php"; ?>