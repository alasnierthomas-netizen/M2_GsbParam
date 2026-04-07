<div id="produits">
<?php
// parcours du tableau contenant les produits à afficher
foreach( $lesProduits as $unProduit) 
{ 	
	$article = $unProduit;
	$lienActionPrincipale = "index.php?uc=gererPanier&produit=".$article->id."&action=ajouterAuPanier";
	$imagePrincipale = "assets/images/mettrepanier.png";
	$titleAction = "Ajouter au panier";
	$altAction = "Mettre au panier";
	include('v_article.php');
}
?>
</div>
