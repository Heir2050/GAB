<script>
    // Attendre que la page soit complètement chargée
    window.onload = function() {
        // Sélectionner l'élément avec l'ID "message"
        var messageDiv = document.getElementById('message');
        // S'il existe, cacher l'élément après 10 secondes
        if (messageDiv) {
            setTimeout(function() {
                messageDiv.style.display = 'none';
            }, 10000); // 10000 millisecondes = 10 secondes
        }
    }
</script>


<?php
    include "header.php";
    include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}

# Afficher tous les guichets automatiques avec localisation complète
$sql = "
    SELECT 
        gc.id_guichet, 
        gc.nom_guichet, 
        bq.nom_banque, 
        pr.nom_province, 
        cm.nom_commune, 
        zn.nom_zone, 
        qt.nom_quartier
    FROM 
        guichet AS gc
    JOIN 
        banque AS bq ON gc.id_banque = bq.id_banque
    JOIN 
        quartier AS qt ON gc.id_quartier = qt.id_quartier
    JOIN 
        zone AS zn ON qt.id_zone = zn.id_zone
    JOIN 
        commune AS cm ON zn.id_commune = cm.id_commune
    JOIN 
        province AS pr ON cm.id_province = pr.id_province
    ORDER BY 
        gc.id_guichet DESC;
";

$statement = $bd->query($sql);  // Exécuter la requête SQL

// Récupérer tous les guichets avec leur banque et localisation
$guichets = $statement->fetchAll(PDO::FETCH_ASSOC);


$message = "";
#Suppression des données
if (isset($_GET['delete'])) {
    $delete = $bd->query("DELETE FROM guichet WHERE id_guichet=".$_GET['delete']);

    header("location:guichet.php");

    $message = "Supprimé avec succès";
}

?>

<style>
    td {
        padding: .5rem;
    }
</style>

<section>
    <h1 class="titre">Liste de tous les guichets</h1>
    <div class="cont">
    <?php if (!empty($message)): ?>
        <div id="message" style="background-color: #d4edda; padding: 10px; border: 1px solid #c3e6cb; color: #155724; margin-bottom: 15px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
        <?php if ($statement): ?>
            <table style="width: 90%;">
                <tr>
                    <th>ID</th>
                    <th>Nom de du guichet</th>
                    <th>Nom de la banque</th>
                    <th>Province</th>
                    <th>Commune</th>
                    <th>Zone</th>
                    <th>Quartier</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($guichets as $guichet): ?>
                    <tr>
                        <td><?= htmlspecialchars($guichet['id_guichet']) ?></td>
                        <td><?= htmlspecialchars($guichet['nom_guichet']) ?></td>
                        <td><?= htmlspecialchars($guichet['nom_banque']) ?></td>
                        <td><?= htmlspecialchars($guichet['nom_province']) ?></td>
                        <td><?= htmlspecialchars($guichet['nom_commune']) ?></td>
                        <td><?= htmlspecialchars($guichet['nom_zone']) ?></td>
                        <td><?= htmlspecialchars($guichet['nom_quartier']) ?></td>
                        <td class="ds">
                            <a href="up_guichet.php?id_guichet=<?= $guichet["id_guichet"]; ?>" class="bts warning">Modifier</a>
                            <a href="a_guichet.php?delete=<?= $guichet["id_guichet"]; ?>" class="bts danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include "footer.php"; ?>