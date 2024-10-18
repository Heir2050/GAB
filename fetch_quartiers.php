<?php
    require 'config.php';
    $zoneId = $_GET['zone'];
    $query = $bd->prepare("SELECT * FROM quartier WHERE id_zone = ?");
    $query->execute([$zoneId]);
    $quartiers = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($quartiers);
?>
