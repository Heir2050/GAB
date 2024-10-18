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
    // Initialisation des variables et nettoyage des entrées
    $nom = trim($_POST['nom']);
    $banque = trim($_POST['banque']);
    $quartier = trim($_POST['quartier']);

    // Vérification de l'email
    if (empty($nom)) {
        $errors['nom'] = "Le nom du guichet est obligatoire.";
    } else {
        // Vérification si l'email existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM guichet WHERE nom_guichet = :nom");
        $query->bindParam(':nom', $nom, PDO::PARAM_STR);
        $query->execute();
        $emailExists = $query->fetchColumn();

        if ($emailExists > 0) {
            $errors['nom'] = "Cet nom du guichet existe déjà.";
        }
    }

    if (empty($banque)) {
        $errors['banque'] = "Le nom du banque est obligatoire.";
    }

    if (empty($quartier)) {
        $errors['quartier'] = "Le status du guichets est obligatoire.";
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO guichet (nom_guichet, id_banque, id_quartier) VALUES (:nom, :banque, :quartier)");
        $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
        $insert->bindParam(':banque', $banque, PDO::PARAM_STR);
        $insert->bindParam(':quartier', $quartier, PDO::PARAM_STR);

        $insert->execute();

        header("location:a_guichet.php");
    }
}

    # Affiché de tous les Banque
    $sql = "SELECT * FROM banque";
    $statement = $bd->query($sql);

    // Recuperer tous les banques
    $banques = $statement->fetchAll(PDO::FETCH_ASSOC);

    # Affiché de tous les quartier
    $sql = "SELECT * FROM quartier";
    $statement = $bd->query($sql);

    // Recuperer tous les quartiers
    $quartiers = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <h1 class="titre">Guichets automatique</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <div class="hed_list">
                    <h3 class="title">Ajouter un guichet</h3>
                    <a href="a_guichet.php" class="btn">Afficher la liste</a>
                </div>
                <div class="flex_d">
                    <div class="column">
                        <div class="input-box">
                            <span>Nom du guichet:</span>
                            <input type="text" name="nom" placeholder="Ajouter le nom du guichet">
                            <small class="message_text"><?= isset($errors['nom']) ? $errors['nom'] : '' ?></small>
                        </div>
                        <div class="input-box">
                            <span>Banque</span>
                            <select name="banque">
                                <option default></option>
                                <?php foreach ($banques as $banque): ?>
                                    <option value="<?= htmlspecialchars($banque['id_banque']) ?>"><?= htmlspecialchars($banque['nom_banque']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="message_text"><?= isset($errors['banque']) ? $errors['banque'] : '' ?></small>
                        </div>
                        <div class="input-box">
                            <span>Quartier</span>
                            <select name="quartier">
                                <option default></option>
                                <?php foreach ($quartiers as $quartier): ?>
                                    <option value="<?= htmlspecialchars($quartier['id_quartier']) ?>"><?= htmlspecialchars($quartier['nom_quartier']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="message_text"><?= isset($errors['quartier']) ? $errors['quartier'] : '' ?></small>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn" name="ajouter" value="Ajouter un guichet">
            </form>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>