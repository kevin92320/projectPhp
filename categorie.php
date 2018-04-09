<?php
// afficher le nom de la catégorie dont on a reçu l'id dans l'url en titre de la page 
// lister les produits appartenant a la categorie avec leur photo s'ils en ont une 
require_once __DIR__ . '/include/init.php';

include __DIR__ . '/layout/top.php';

$query = 'SELECT nom FROM categorie WHERE id = ' . $_GET['id'];
$stmt = $pdo->query($query);
$categorie = $stmt->fetch();



?>

<h2><?= $categorie['nom']; ?> </h2>

<?php

$query= 'SELECT * FROM produits WHERE categorie_id= '.$_GET['id'];
$stmt= $pdo->query($query);
$produits= $stmt->fetchAll();
?>

    <?php
    foreach ($produits as $produit) :
        $src = (!empty($produit['photo']))
        ? PHOTO_WEB . $produit['photo']
        : PHOTO_DEFAULT
        ;
    ?>

    <div class="col-sm-3">
        <div class="card">
            <img src="<?= $src; ?>" alt="" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?= $produit['nom']; ?></h5>
                    <p class="card-text"><?= prixFR($produit['prix']); ?></p>
                    <p class="card-text text-center">
                    <a href="produit.php?id=<?= $produit['id']; ?>" class="btn btn-primary">Voir</a>
                    </p>
                 </div>
        </div>
    </div>
       
    <?php        
    endforeach;
?>


</table>

<?php

include __DIR__ . '/layout/bottom.php';
?>