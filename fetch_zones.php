<?php
    require 'config.php';
    $communeId = $_GET['commune'];
    $query = $bd->prepare("SELECT * FROM zone WHERE id_commune = ?");
    $query->execute([$communeId]);
    $zones = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($zones);
?>
