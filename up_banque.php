<?php
    include "header.php"; 
    include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}

$errors =[];

// Get the Banque element for doing an update
$stmt = $bd->query('SELECT * FROM banque WHERE id_banque ='.$_GET['update']);
$guichet = $stmt->fetch(PDO::FETCH_ASSOC);

# Modification des éléments
if (isset($_POST['update'])) {
    $id_banque = $_POST['id_banque'];
    $banque = $_POST['banque'];

    if (empty($banque)) {
        $errors['banque'] = "Le nom de la banque est obligatoire.";
    }

    // Utilisation de requêtes préparées pour éviter les injections SQL
    if (empty($errors)) {
        $update = $bd->prepare("UPDATE banque SET nom_banque = :banque WHERE id_banque = :id_banque");
        $update->bindParam(':id_banque', $id_banque, PDO::PARAM_INT);
        $update->bindParam(':banque', $banque, PDO::PARAM_STR);

        $update->execute();

        header("location:a_banque.php");
    }
}

var_dump($errors);
?>

<section>
    <h1 class="titre">Banque</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <div class="hed_list">
                    <h3 class="title">Modifier une banque</h3>
                    <a href="a_banque.php" class="btn">Afficher la liste</a>
                </div>
                <div class="column">
                    <input type="hidden" name="id_banque" value="<?php echo $guichet['id_banque']; ?>">
                    <div class="input-box">
                        <span>Nom de la banque :</span>
                        <input type="text" name="banque" value="<?php echo $guichet['nom_banque']; ?>">
                        <small class="message_text"><?= isset($errors['banque']) ? $errors['banque'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="update">Modifier</button>
            </form>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>