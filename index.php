<!-- Ce fichier Javascript permet d'effectuer le recheche en tant reel  -->
<script src="assets/js/search_results.js"></script>

<?php
include "header.php";
include "config.php";

# Affiché de tous les Province
$provinces = $bd->query("SELECT * FROM province")->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    // Récupérer les valeurs sélectionnées
    $id_province = $_GET['province'] ?? null;
    $id_commune = $_GET['commune'] ?? null;
    $id_zone = $_GET['zone'] ?? null;
    $id_quartier = $_GET['quartier'] ?? null;

    // Construire la requête SQL dynamique
    $sql = "
        SELECT gc.id_guichet, gc.nom_guichet, bq.nom_banque, qt.nom_quartier, zn.nom_zone, cm.nom_commune, pr.nom_province
        FROM guichet AS gc
        JOIN banque AS bq ON gc.id_banque = bq.id_banque
        JOIN quartier AS qt ON gc.id_quartier = qt.id_quartier
        JOIN zone AS zn ON qt.id_zone = zn.id_zone
        JOIN commune AS cm ON zn.id_commune = cm.id_commune
        JOIN province AS pr ON cm.id_province = pr.id_province
        WHERE 1=1
    ";

    // Ajouter des conditions à la requête en fonction des valeurs sélectionnées
    if ($id_province) {
        $sql .= " AND pr.id_province = :id_province";
    }
    if ($id_commune) {
        $sql .= " AND cm.id_commune = :id_commune";
    }
    if ($id_zone) {
        $sql .= " AND zn.id_zone = :id_zone";
    }
    if ($id_quartier) {
        $sql .= " AND qt.id_quartier = :id_quartier";
    }

    $stmt = $bd->prepare($sql);

    // Lier les paramètres en fonction de leur disponibilité
    if ($id_province) {
        $stmt->bindParam(':id_province', $id_province, PDO::PARAM_INT);
    }
    if ($id_commune) {
        $stmt->bindParam(':id_commune', $id_commune, PDO::PARAM_INT);
    }
    if ($id_zone) {
        $stmt->bindParam(':id_zone', $id_zone, PDO::PARAM_INT);
    }
    if ($id_quartier) {
        $stmt->bindParam(':id_quartier', $id_quartier, PDO::PARAM_INT);
    }

    // Exécuter la requête et récupérer les résultats
    $stmt->execute();
    $guichets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<style>
    .form-group {
        display: flex;
        gap: 1rem;
    }

    .search {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 50vh;
    }

    .search p {
        margin-bottom: 2rem;
        font-weight: 500;
        text-align: center;
    }

    .error {
        text-align: center;
        font-weight: 500;
        background: #62cef9;
        max-width: 50%;
        margin: 0 auto;
        padding: 1rem;
        border-radius: .5rem;
    }
</style>

<section class="search">
    <div class="container">
        <h1 class="titre">GAB</h1>
        <p>Retrouver un guichet automatique les plus proches de vous !</p>
        <form action="" method="GET" style="width: 80%;margin: 0 auto;">
            <div class="form-group">
                <select name="province" id="province" onchange="fetchCommunes(this.value)">
                    <option default>Sélectionner une province</option>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?= $province['id_province'] ?>"><?= htmlspecialchars($province['nom_province']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="commune" id="commune" onchange="fetchZones(this.value)">
                    <option value="">Sélectionnez une commune</option>
                </select>

                <select name="zone" id="zone" onchange="fetchQuartiers(this.value)">
                    <option value="">Sélectionnez une zone</option>
                </select>
                <select name="quartier" id="quartier">
                    <option value="">Sélectionnez un quartier</option>
                </select>
            </div>
            <button name="search" class="btn" type="submit" style="width: 30%; margin: 0 auto; display: flex; margin-top: 1rem;justify-content: space-around;">Rechercher</button>
        </form>
    </div>
</section>

<?php if (!empty($guichets) && isset($_GET['search'])): ?>
    <h1 class="titre">Résultats de recherche</h1>
    <div class="cont">
        <table style="width: 90%;">
            <tr>
                <th>Nom de du guichet</th>
                <th>Nom de la banque</th>
                <th>Province</th>
                <th>Commune</th>
                <th>Zone</th>
                <th>Quartier</th>
            </tr>
            <?php foreach ($guichets as $guichet): ?>
                <tr>
                    <td><?= htmlspecialchars($guichet['nom_guichet']) ?></td>
                    <td><?= htmlspecialchars($guichet['nom_banque']) ?></td>
                    <td><?= htmlspecialchars($guichet['nom_province']) ?></td>
                    <td><?= htmlspecialchars($guichet['nom_commune']) ?></td>
                    <td><?= htmlspecialchars($guichet['nom_zone']) ?></td>
                    <td><?= htmlspecialchars($guichet['nom_quartier']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php elseif((empty($guichets) && isset($_GET['search']))): ?>
    <p class="error">Aucun guichet trouvé pour les critères sélectionnés.</p>
<?php endif; ?>


<?php include "footer.php"; ?>