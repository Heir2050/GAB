<?php
    require 'config.php';
    $provinceId = $_GET['province'];
    $query = $bd->prepare("SELECT * FROM commune WHERE id_province = ?");
    $query->execute([$provinceId]);
    $communes = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($communes);
?>
