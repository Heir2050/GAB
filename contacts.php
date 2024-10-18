<?php
include "header.php";
include "config.php";


$errors = []; // Pour stocker les erreurs

if (isset($_POST['ajouter'])) {
    // Initialisation des variables et nettoyage des entrées
    $numer_tel = trim($_POST['tel']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Vérification de l'email
    if (empty($email)) {
        $errors['email'] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Le format de l'email n'est pas valide.";
    } else {
        // Vérification si l'email existe déjà dans la base de données
        $query = $bd->prepare("SELECT COUNT(*) FROM contacts WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $emailExists = $query->fetchColumn();

        if ($emailExists > 0) {
            $errors['email'] = "Cet email existe déjà.";
        }
    }
    // Vérification du téléphone (simple validation pour 8 chiffres)
    if (empty($numer_tel)) {
        $errors['numer_tel'] = "Le numéro de téléphone est obligatoire.";
    } elseif (!preg_match("/^[0-9]{8}$/", $numer_tel)) {
        $errors['numer_tel'] = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
    }

    if (empty($message)) {
        $errors['message'] = "Le message est obligatoire.";
    }

    // Si il y a pas d'erreur, alors insert les données dans la base des données
    if (empty($errors)) {
        $insert = $bd->prepare("INSERT INTO contacts (numer_tel, email, message) VALUES (:numer_tel, :email, :message)");
        $insert->bindParam(':numer_tel', $numer_tel, PDO::PARAM_STR);
        $insert->bindParam(':email', $email, PDO::PARAM_STR);
        $insert->bindParam(':message', $message, PDO::PARAM_STR);

        $insert->execute();

    }
}



# Affichage des donné
$sql = "SELECT * FROM contacts; WHERE numero";
$statement = $bd->query($sql);  // Executer la requette sql

// Avoire toute les banques
$contacts = $statement->fetchAll(PDO::FETCH_ASSOC);

#Suppression des elements
if (isset($_GET['delete'])) {
    $delete = $bd->query("DELETE FROM contacts WHERE id_contact=".$_GET['delete']);

    header("location:index.php");
}

#Suppression des elements
if (isset($_GET['delete'])) {
    $delete = $bd->query("DELETE FROM contacts WHERE id_contact=".$_GET['delete']);

    header("location:index.php");
}

?>

<section>
    <h1 class="titre">Contacts</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;">
                <!-- <strong></strong> -->
                <!-- <h3 class="title">Ajouter un quartier</h3> -->
                <div class="column">
                    <div class="input-box">
                        <span>Téléphone:</span>
                        <input type="text" name="tel" placeholder="Votre numero de téléphone">
                            <?php if(!empty($errors)): ?>
                                <small class="message_text"><?= isset($errors['numer_tel']) ? $errors['numer_tel'] : '' ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <span>Email:</span>
                        <input type="email" name="email" placeholder="Ajouter un quartier">
                        <small class="message_text"><?= isset($errors['email']) ? $errors['email'] : '' ?></small>
                    </div>
                    <div class="input-box">
                        <span>Message:</span>
                        <textarea name="message" cols="66" rows="10"></textarea>
                        <small class="message_text"><?= isset($errors['message']) ? $errors['message'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="ajouter">Envoyer</button>
            </form>
        </div>
    </div>
</section>


<?php include "footer.php"; ?>