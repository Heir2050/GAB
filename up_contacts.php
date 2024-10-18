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
$stmt = $bd->query('SELECT * FROM contacts WHERE id_contact ='.$_GET['id_contact']);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);


// Suppression d'un contact
if (isset($_GET['delete'])) {
    // Utilisation de requêtes préparées pour éviter les injections SQL
    $delete = $bd->prepare("DELETE FROM contacts WHERE id_contact = :id_contact");
    $delete->bindParam(':id_contact', $_GET['delete'], PDO::PARAM_INT);
    $delete->execute();

    header("location:a_contacts.php");
}

// Modification des éléments
if (isset($_POST['update'])) {
    $id_contact = $_POST['id_contact'];
    $numer_tel = $_POST['tel'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Utilisation de requêtes préparées pour éviter les injections SQL
    $update = $bd->prepare("UPDATE contacts SET numer_tel = :numer_tel, email = :email, message = :message WHERE id_contact = :id_contact");
    $update->bindParam(':numer_tel', $numer_tel, PDO::PARAM_STR);
    $update->bindParam(':email', $email, PDO::PARAM_STR);
    $update->bindParam(':message', $message, PDO::PARAM_STR);
    $update->bindParam(':id_contact', $id_contact, PDO::PARAM_INT);

    $update->execute();

    header("location:contacts.php");
}
?>

<section>
    <h1 class="titre">Contacts</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" enctype="multipart/form-data" style="width: 500px;">
                <!-- <strong></strong> -->
                <h3 class="title">Modifier un quartier</h3>
                <div class="column">
                    <input type="hidden" name="id_contact" value="<?php echo $contact['id_contact']; ?>">
                    <div class="input-box">
                        <span>Téléphone:</span>
                        <input type="text" name="tel" value="<?= $contact['numer_tel']; ?>" placeholder="Votre numero de téléphone">
                    </div>
                    <div class="input-box">
                        <span>Email:</span>
                        <input type="email" name="email" value="<?php echo $contact['email']; ?>" placeholder="Ajouter un quartier">
                    </div>
                    <div class="input-box">
                        <span>Message:</span>
                        <textarea name="message" id="" cols="66" rows="10"><?php echo $contact['message']; ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn" name="update">Ajouter un quartier</button>
            </form>
        </div>
    </div>
</section>



<?php include "footer.php"; ?>