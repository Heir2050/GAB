<?php

include "header.php";
include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}


# Affichage des donnés

$sql = "SELECT * FROM banque;";
$statement = $bd->query($sql);  // Executer la requette sql

// Avoire toute les banques
$banques = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<section>
    <h1 class="titre">Affichage de tous les Banques</h1>

<section>
    <div class="cont">
        <?php if ($statement): ?>
            <table style="width: 60%;">
                <tr>
                    <th>ID</th>
                    <th>Nom de la banque</th>
                    <th>date d'ajout</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($banques as $banque): ?>
                    <tr>
                        <td><?= htmlspecialchars($banque['id_banque']) ?></td>
                        <td><?= htmlspecialchars($banque['nom_banque']) ?></td>
                        <td><?= htmlspecialchars($banque['date_creation']) ?></td>
                        <td class="ds">
                            <a href="up_banque.php?update=<?= $banque["id_banque"]; ?>" class="bts warning">Modifier</a>
                            <a href="index.php?delete=<?= $banque["id_banque"]; ?>" class="bts danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>


<?php include "footer.php"; ?>