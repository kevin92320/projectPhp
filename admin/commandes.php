<?php
// Lister les commandes dans un tableau HTML :
// -id d ela commande 
// - nom prenom de l'utilisateur qui a passé la commande 
// - le montant formaté
// -date de la commande formaté (function date() et strtotime de PHP)
// -statut
// date de la statut formatée (function date() et strtotime de PHP)
// Passer le statut en liste déroulante (en cours, envoyé, livré)
//     avec un bouton modifier pour, changer le statut de la commande 
//     => traiter le changement de statut en mettant à jour statut et date_statut dans la table commande

require_once __DIR__ . '/../include/init.php';
adminSecurity();



if (isset($_POST['modifier-statut'])) {
    $query = 'UPDATE commande SET'
        . ' statut = :statut,'
        . ' date_statut = now()'
        . ' WHERE id = :id'
    ;

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':statut', $_POST['statut']);
    $stmt->bindValue(':id', $_POST['commande-id']);
    $stmt->execute();

    setFlashMessage('le statut est modifié');

}


$query = "SELECT c.*, concat_ws(' ', u.nom, u.prenom)  AS utilisateur"
    . ' FROM commande c'
    . ' JOIN utilisateur u ON c.utilisateur_id = u.id';

    $stmt = $pdo->query($query);
$commandes = $stmt->fetchAll();


$statuts =[
    'livré',
    'en cours',
    'envoyé'
];

include __DIR__ . '/../layout/top.php';
// $query = "SELECT c.*, CONCAT_WS(' ' ,u.nom, u.prenom) AS utilisateur FROM commande c JOIN utilisateur u ON c.Utilisateur_id = u.id";



// echo '<pre>';
// var_dump($commandes);
// echo '</pre>';

?>

<h2>GESTION DES COMMANDES</h2>
        

<table class="table">
    <tr>
        <th>id</th>
        <th>Utilisateur</th>
        <th>Montant total</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Date Mise a jour Statut</th>
    </tr>


<?php
foreach ($commandes as $commande) :
?>
        <tr>
            <td><?= $commande['id']; ?></td>
            <td><?= $commande['utilisateur']; ?></td>
            <td><?= prixFr($commande['montant_total']); ?></td>
            <td><?= dateFr($commande['date_commande']); ?></td>

            <td>
                <form method="post" class="form-inline">
                    <select name="statut" class="form-control">
                    <?php
                        foreach ($statuts as $statut) :
                            $selected = ($statut == $commande['statut'])
                            ? 'selected'
                            : ''
                        ;
                    ?>
                           
                    
            <option value="<?= $statut; ?>" <?= $selected; ?>>
            <?= ucfirst($statut); ?>
            </option> 

            <?php        
            endforeach;
            ?>

            </select>
            <input type="hidden" name="commande-id" value="$commande['id']">
            <button class="btn btn-primary" type="submit" name="modifier-statut">Valider
            </button>
        
            </form>
          
            <td><?= $commande['date_statut']; ?></td>
        </tr>

    <?php        
    endforeach;
    ?>

</table>


<?php

include __DIR__ . '/../layout/bottom.php';
?>