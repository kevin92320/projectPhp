<?php

function setFlashMessage($message, $type = 'success')
{
    $_SESSION['flashMessage'] = [
        'message' => $message,
        'type' => $type 

    ];
}

function displayFlashMessage ()
{
    if (isset($_SESSION['flashMessage'])) {
        $message = $_SESSION['flashMessage']['message'];
        $type = ($_SESSION['flashMessage']['type']== 'error')
            ? 'danger' // pour la classe alert-danger du bootstrap
            : $_SESSION ['flashMessage']['type']
        ;

        echo '<div class="alert alert-' . $type . '" >'
            . '<h5 class="alert-heading">' . $message . '<h5>'
            . '</div>'
            ;

        // suppression du msg de la session pour affichage "one shot"
        unset ($_SESSION['flashMessage']);
    }
}

function sanitizeValue(&$value)
{
    // trim() supprime les espaces en début et fin de chaîne
    // strip_tags() supprime les balises HTML
    $value = trim(strip_tags($value));
}

function sanitizeArray(array &$array)
{
    // applique la fonction sanitizeValue() sur les éléments du tableau
    array_walk($array, 'sanitizeValue');
}

function sanitizePost()
{
    sanitizeArray($_POST);
}

function isUserConnected () 
{
    return isset($_SESSION['utilisateur']);
}

function getUserFullName ()
{
    if (isUserConnected()) {
        return $_SESSION['utilisateur']['prenom']
            .' '. $_SESSION ['utilisateur']['nom']
        ;
    }
}

function isUserAdmin () 
{
    return isUserConnected() && $_SESSION['utilisateur']['role'] == 'admin';
}

function adminSecurity() 
{
    if (!isUserAdmin()){
        if (!isUserConnected()) {
            header('Location: ' . RACINE_WEB . 'connexion.php');
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo "Vous n'avez pas le droit d'accéder à cette page";
        }

        die;

    }
}

function prixFr($prix)
{
    return number_format($prix, 2, ',', ' ') . ' €';
}

function dateFR ($dateSql)
{
    return date('d/m/Y H:i:s', strtotime($dateSql));
}

function ajoutPanier (array $produit, $quantite)
{
    // initialisation du panier 
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // 
    if(!isset($_SESSION['panier'][$produit['id']])) {
        $_SESSION ['panier'][$produit['id']] = [
            'nom' => $produit['nom'],
            'prix' => $produit['prix'],
            'quantite' => $quantite
        ];

    } else {
        // si le produit est deja dans le panier on met a jour la quantité
        $_SESSION['panier'][$produit['id']]['quantite'] += $quantite;
    }
}

function getTotalPanier()
{
    $total = 0;

    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
    }

    return $total;
    
}


function modifierQuantitePanier($produitId, $quantite)
{
    if (isset($_SESSION['panier'][$produitId])) {
        if ($quantite != 0) {
            $_SESSION['panier'][$produitId]['quantite'] = $quantite;
        } else {
            unset($_SESSION['panier'][$produitId]);
        }
    }
}


