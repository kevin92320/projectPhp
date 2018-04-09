<?php
// si le panier est vide : afficher un message 
// sinon afficher un tablau Html avec pour chaque produit du panier :
// nom du produit, prix unitaire, quantite, prix total pour le produit 
// faire une fonction getTotalPanier() qui calcule le montant total du panier et l'utiliser sous le tableau pour afficher le total
// remplacer l'affichage de la quantite par un formulaire avec 
//      - <input type="number">
//      - un input hidden pour voir l'id du produit dont on modifie la qté
//      - un bouton submit 
//  faire un fonction modifierQuantitéPanier() qui met à jour la quantité pour le produit si la quantité n'est pas 0, et qui supprime le produit du panier sinon appeler cette fonction quand un des formulaire est renvoyé

require_once __DIR__ . '/include/init.php';
include __DIR__ . '/layout/top.php';



if (isset($_POST['commander'])) {


    $query = <<<EOS
INSERT INTO commande (
    utilisateur_id,
    montant_total
    ) VALUES (
        :utilisateur_id,
        :montant_total
    )
EOS;

    $stmt = $pdo->prepare($query);
    $stmt->bindValue (':utilisateur_id', $_SESSION['utilisateur']['id']);
    $stmt->bindValue (':montant_total', getTotalPanier());
    $stmt->execute();
    $commandeId = $pdo->lastInsertId();
    
    $query = <<<EOS
INSERT INTO detail_commande (
    commande_id,
    produit_id,
    prix,
    quantite
) VALUES (
    :commande_id,
    :produit_id,
    :prix,
    :quantite
)
EOS;

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':commande_id', $commandeId);

    foreach ($_SESSION['panier'] as $produitId => $produit) {
        $stmt->bindValue(':produit_id', $produitId);
        $stmt->bindValue(':prix', $produit['prix']);
        $stmt->bindValue(':quantite', $produit['quantite']);
        $stmt->execute();
    }

    setFlashMessage('La commande est enregistrée');
    // on vide le panier
    $_SESSION['panier'] = [];
}


if (isset($_POST['modifier-quantite'])) {
    modifierQuantitePanier($_POST['produit-id'], $_POST['quantite']);
    setFlashMessage('La quantité est modifie');
}

?>

<h1>Panier</h1>

<?php
if (empty($_SESSION ['panier'])) :
?>

<div class="alert alert-info">
Le panier est vide
</div>


<?php
else :       
?>

<table class="table">
<tr>
        <th>nom</th>
        <th>prix unitaire</th>
        <th>quantite</th>
        <th>prix total</th>
</tr>             

<?php 

foreach ($_SESSION['panier'] as $id => $produit):
?> 

<tr>
    <td><?= $produit['nom']; ?></td>
    <td><?= prixFr($produit['prix']); ?></td>
    <td>
    <form method="post">
        <input type="number" 
        name="quantite" 
        min="0"
        value="<?= $produit['quantite']; ?>"
        class="form-control">

        <input type="hidden" name="produit-id" value="<?= $id ?>">

        <button class="btn btn-primary" type="submit" name="modifier-quantite">Modifier</button>

    </form>
    </td>
    <td><?= prixFr($produit['prix'] * $produit['quantite']); ?></td>
</tr>       

<?php 
   endforeach;
?>

<tr>
    <td colspan="3">Total</th>
    <td><?= prixFr(getTotalPanier()); ?></td>
</tr>
<?php 
   endif;
?>
</table>

<?php 
   if(isUserConnected()) :
?>

<form method="post">
    <p class="text-right">
        <button type="submit" name="commander" class="btn btn-primary">
            valider la commande
        </button>
    </p>
</form>

<?php 
   else :
?>

    <div class="alert alert-info">
    Vous devez vous connecter ou vous inscrire pour valider la commande
    </div>


<?php 
   endif;
?>

<?php
include __DIR__ . '/layout/bottom.php';
?>