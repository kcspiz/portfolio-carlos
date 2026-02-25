<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8', 'root', '');

// Vérifie que le formulaire est bien envoyé en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $desc = htmlspecialchars($_POST['desc']);
    $cat = htmlspecialchars($_POST['cat']);
    $lien = isset($_POST['lien']) ? htmlspecialchars($_POST['lien']) : null;

    // Gestion des captures d'écran
    $captures = [];
    if (!empty($_FILES['captures']['name'][0])) {
        foreach ($_FILES['captures']['tmp_name'] as $i => $tmp_name) {
            $file_name = uniqid('capture_') . '_' . basename($_FILES['captures']['name'][$i]);
            $target = 'uploads/captures/' . $file_name;
            if (move_uploaded_file($tmp_name, $target)) {
                $captures[] = $target;
            }
        }
    }

    // Gestion de la vidéo
    $videoPath = null;
    if (!empty($_FILES['video']['tmp_name'])) {
        $file_name = uniqid('video_') . '_' . basename($_FILES['video']['name']);
        $target = 'uploads/videos/' . $file_name;
        if (move_uploaded_file($_FILES['video']['tmp_name'], $target)) {
            $videoPath = $target;
        }
    }

    // Insertion en base
    $stmt = $pdo->prepare("INSERT INTO projets (titre, description, categorie, lien, captures, video) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $titre,
        $desc,
        $cat,
        $lien,
        json_encode($captures),
        $videoPath
    ]);
    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Requête invalide']);