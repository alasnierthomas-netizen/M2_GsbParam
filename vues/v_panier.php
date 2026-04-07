<div class="alert alert-light" role="alert" id="panier">Votre panier :</div>
<a href="index.php?uc=gererPanier&action=supprimerPanier"><button type="button" class="btn btn-primary" onclick="return confirm('Voulez-vous vraiment vider le panier ?');">suprimer</button></a>

<div id="produits">
<?php
foreach( $lesProduitsDuPanier as $unProduit) 
{
	$article = $unProduit;
	$lienActionPrincipale = "index.php?uc=gererPanier&produit=".$article->id."&action=suprimerDuPanier";
	$imagePrincipale = "assets/images/retirerpanier.png";
	$titleAction = "Retirer du panier";
	$altAction = "retirer du panier";
	$confirmMessage = "Voulez-vous vraiment retirer cet article ?";
	include('v_article.php');
}
?>
</div>
<div class="contenuCentre">
<a href="index.php?uc=gererPanier&action=passerCommande" class="btn btn-primary">Commander</a>
</div>
