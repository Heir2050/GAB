<?php
    include "header.php";
    include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}


    # Affichage des donné

    $sql = "SELECT * FROM contacts;";
    $statement = $bd->query($sql);  // Executer la requette sql

    // Avoire toute les banques
    $contacts = $statement->fetchAll(PDO::FETCH_ASSOC);

#Suppression des elements
if (isset($_GET['delete'])) {
    $delete = $bd->query("DELETE FROM contacts WHERE id_contact=".$_GET['delete']);
    header("location:a_contacts.php");
}

/*
//Modification des éléments
if (isset($_POST['update'])) {
    $id_contact = $_POST['id_contact'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    $update = $bd->query("UPDATE contacts SET nom='$nom', email='$email', telephone='$telephone' WHERE id_contact=$id_contact");

    header("location:index.php");
}
*/
?>
<section>
    <h3 class="titre">Liste de contacts</h3>
    <div class="cont">
        <?php if ($statement): ?>
            <table style="width: 70%;">
                <tr>
                    <th>ID</th>
                    <th>Numero de Téléphone</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['id_contact']) ?></td>
                        <td><?= htmlspecialchars($contact['numer_tel']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['message']) ?></td>
                        <td class="ds">
                            <a href="up_contacts.php?id_contact=<?= $contact["id_contact"]; ?>" class="bts danger" style="margin-right: .5rem">Modifier</a>
                            <a href="a_contacts.php?delete=<?= $contact["id_contact"]; ?>" class="bts warning">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include "footer.php"; ?>