<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8', 'root', '');
$projets = $pdo->query("SELECT * FROM projets ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($projets as &$projet) {
    $projet['captures'] = $projet['captures'] ? json_decode($projet['captures']) : [];
}
echo json_encode($projets);