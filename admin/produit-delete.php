<?php

require_once __DIR__ . '/../include/init.php';
adminSecurity();

$query = 'SELECT photo FROM produits WHERE id = ' . $_GET['id'];
$stmt = $pdo->query($query);
$photo = $stmt->fetchColumn(); 


//On supprime l'image du produit dans le repertoire photo s'il en a un
if (!empty($photo)) {
    unlink(PHOTO_DIR . $photoActuelle);
}

$query = 'DELETE FROM produits WHERE id = ' . $_GET['id'];
$pdo->exec($query);



setFlashMessage('Le produit est supprimé');
header('Location: produits.php');
die;

?>