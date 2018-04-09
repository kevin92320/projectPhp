<?php
require_once __DIR__ . '/../include/init.php';
adminSecurity();


// Lister les catégories dans un tableau HTML

// Le requêtage ici
$query = 'SELECT * FROM categorie';
$stmt = $pdo->query($query);
 
$categories = $stmt->fetchAll();

include __DIR__ . '/../layout/top.php';
?>

<h1>Gestion catégories</h1>

<?php

?>

<p>
<a class="btn btn-dark" href="categorie-edit.php">Ajouter une catégorie</a></p>



<table class="table">
    <tr>
        <th>id</th>
        <th>nom</th>
        <th width="250px"></th>
    </tr>
    <?php

    // 1ere facon de faire    

    // foreach ($vetements as $value) :
    //        echo '<tr><td>' . $value['id'] . '</td><td>'  . $value['nom'] . '</td></tr>';
    // endforeach;


    // 2eme facon de faire 

    foreach ($categories as $categorie) :
    ?>
        <tr>
            <td><?= $categorie['id']; ?></td>
            <td><?= $categorie['nom']; ?></td>
            <td>
            <a class="btn btn-info" href="categorie-edit.php?id=<?= $categorie['id']; ?>">Modifier </a>
            <a class="btn btn-danger" href="categories-delete.php?id=<?= $categorie['id']; ?>">Supprimer </a>
            </td>
        </tr>
    <?php        
    endforeach;
?>


</table>


<?php
include __DIR__ . '/../layout/bottom.php';
?>
